<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TopUpController extends Controller
{
    /**
     * Top-Up package definition list.
     */
    private function getPackages()
    {
        return [
            'coins' => [
                [
                    'id' => 'coin_pouch',
                    'name' => 'Pouch of Coins',
                    'qty' => 1000,
                    'price' => 10000,
                    'description' => 'A small leather pouch filled with shiny gold coins.',
                    'icon' => '🟡'
                ],
                [
                    'id' => 'coin_chest',
                    'name' => 'Chest of Coins',
                    'qty' => 5500,
                    'price' => 50000,
                    'description' => 'A sturdy iron-bound chest containing a wealth of gold.',
                    'icon' => '📦'
                ],
                [
                    'id' => 'coin_vault',
                    'name' => 'Vault of Coins',
                    'qty' => 12000,
                    'price' => 100000,
                    'description' => 'A royal vault\'s bounty. Enough to purchase premium gears!',
                    'icon' => '🏛️'
                ],
            ],
            'diamonds' => [
                [
                    'id' => 'diamond_shard',
                    'name' => 'Diamond Shard',
                    'qty' => 100,
                    'price' => 15000,
                    'description' => 'A glowing crystal shard emitting magical energies.',
                    'icon' => '💎'
                ],
                [
                    'id' => 'diamond_cluster',
                    'name' => 'Diamond Cluster',
                    'qty' => 550,
                    'price' => 75000,
                    'description' => 'A collection of refined diamonds, highly valued by traders.',
                    'icon' => '🔮'
                ],
                [
                    'id' => 'diamond_mountain',
                    'name' => 'Mountain of Diamonds',
                    'qty' => 1200,
                    'price' => 150000,
                    'description' => 'An absolute mountain of crystal diamonds. Supreme status symbol.',
                    'icon' => '🌋'
                ],
            ]
        ];
    }

    /**
     * Show the Top Up Page.
     */
    public function index()
    {
        return view('pages.topup', [
            'packages' => $this->getPackages()
        ]);
    }

    /**
     * Create Midtrans checkout Snap Token.
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'package_id' => 'required|string',
            'type' => 'required|string|in:coin,diamond'
        ]);

        $packages = $this->getPackages();
        $typeKey = $request->type === 'coin' ? 'coins' : 'diamonds';
        
        $selectedPackage = null;
        foreach ($packages[$typeKey] as $package) {
            if ($package['id'] === $request->package_id) {
                $selectedPackage = $package;
                break;
            }
        }

        if (!$selectedPackage) {
            return response()->json(['error' => 'Invalid package selected.'], 400);
        }

        $user = Auth::user();
        $orderId = 'TRX-' . time() . '-' . rand(100, 999);

        // Map payload for Midtrans
        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $selectedPackage['price'],
            ],
            'item_details' => [
                [
                    'id' => $selectedPackage['id'],
                    'price' => (int) $selectedPackage['price'],
                    'quantity' => 1,
                    'name' => $selectedPackage['name'],
                ]
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ]
        ];

        // Direct cURL to Midtrans Sandbox Snap API
        $serverKey = config('midtrans.server_key');
        $isProduction = config('midtrans.is_production');
        $apiUrl = $isProduction 
            ? 'https://app.midtrans.com/snap/v1/transactions' 
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($serverKey . ':')
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 && $httpCode !== 201) {
            Log::error('Midtrans Snap Error: ' . $response);
            return response()->json(['error' => 'Failed to connect to payment gateway.'], 500);
        }

        $result = json_decode($response, true);
        $snapToken = $result['token'] ?? null;

        if (!$snapToken) {
            return response()->json(['error' => 'Failed to generate snap token.'], 500);
        }

        // Save pending transaction record
        Transaction::create([
            'user_id' => $user->id,
            'order_id' => $orderId,
            'amount' => $selectedPackage['price'],
            'type' => $request->type,
            'package_name' => $selectedPackage['name'],
            'qty' => $selectedPackage['qty'],
            'status' => 'pending',
            'snap_token' => $snapToken
        ]);

        return response()->json([
            'snap_token' => $snapToken,
            'order_id' => $orderId
        ]);
    }

    /**
     * Handle webhook callbacks from Midtrans securely.
     */
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        
        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');

        // Midtrans secure verification signature: SHA512(order_id + status_code + gross_amount + server_key)
        $computedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($computedSignature !== $signatureKey) {
            Log::warning('Midtrans Webhook: Invalid Signature detected.');
            return response()->json(['error' => 'Invalid signature key.'], 403);
        }

        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found.'], 404);
        }

        // Skip if transaction already processed (success / failed / expired)
        if ($transaction->status !== 'pending') {
            return response()->json(['message' => 'Transaction already processed.']);
        }

        $transactionStatus = $request->input('transaction_status');
        $paymentType = $request->input('payment_type');
        $fraudStatus = $request->input('fraud_status');

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $transaction->status = 'pending';
            } else if ($fraudStatus == 'accept') {
                $transaction->status = 'success';
            }
        } else if ($transactionStatus == 'settlement') {
            $transaction->status = 'success';
        } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $transaction->status = $transactionStatus === 'expire' ? 'expired' : 'failed';
        }

        $transaction->save();

        // If payment is successful, credit currency to user balance
        if ($transaction->status === 'success') {
            $user = User::find($transaction->user_id);
            if ($user) {
                if ($transaction->type === 'coin') {
                    $user->coins += $transaction->qty;
                } else if ($transaction->type === 'diamond') {
                    $user->diamonds += $transaction->qty;
                }
                $user->save();
                Log::info("Midtrans topup credit success: User {$user->id} credited with {$transaction->qty} {$transaction->type}s.");
            }
        }

        return response()->json(['message' => 'Callback handled successfully.']);
    }

    /**
     * Local frontend success handler fallback for local development environments
     * where Midtrans servers cannot directly access the local webhook url.
     */
    public function claim(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'status' => 'required|string'
        ]);

        $transaction = Transaction::where('order_id', $request->order_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($transaction && $transaction->status === 'pending' && $request->status === 'success') {
            $transaction->status = 'success';
            $transaction->save();

            $user = User::find($transaction->user_id);
            if ($user) {
                if ($transaction->type === 'coin') {
                    $user->coins += $transaction->qty;
                } else if ($transaction->type === 'diamond') {
                    $user->diamonds += $transaction->qty;
                }
                $user->save();
                Log::info("Local claim topup credit success: User {$user->id} credited with {$transaction->qty} {$transaction->type}s.");
            }
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Transaction already processed or not found.']);
    }
}
