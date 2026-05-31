<?php

namespace App\Http\Controllers;

use App\Models\Seal\SealShopItem;
use App\Models\Seal\SealCegelShopItem;
use App\Models\Seal\SealVending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketplaceController extends Controller
{
    /**
     * Halaman utama marketplace
     */
    public function index(Request $request)
    {
        $tab     = $request->get('tab', 'cash');    // cash | cegel | vending
        $search  = $request->get('search', '');
        $perPage = 20;

        $cashItems   = collect();
        $cegelItems  = collect();
        $vendingItems = collect();

        try {
            // --- CASH SHOP (Diamond/Premium Currency) ---
            $cashQuery = SealShopItem::available();
            if ($search) $cashQuery->search($search);
            $cashItems = $cashQuery->orderBy('num_idx', 'asc')->paginate($perPage)->withQueryString();

            // --- CEGEL SHOP (In-game Currency) ---
            $cegelQuery = SealCegelShopItem::available();
            if ($search) $cegelQuery->search($search);
            $cegelItems = $cegelQuery->orderBy('num_idx', 'asc')->paginate($perPage)->withQueryString();

        } catch (\Exception $e) {
            // Jika DB game belum konek, tampilkan pesan error yang ramah
            session()->flash('db_error', 'Tidak dapat terhubung ke database game. Pastikan password sudah diisi di .env');
        }

        // --- PLAYER VENDING (Personal Shop) ---
        try {
            $vendingItems = SealVending::getActiveListings();
        } catch (\Exception $e) {
            // Silent fail, vending optional
        }

        return view('pages.marketplace', compact(
            'cashItems', 'cegelItems', 'vendingItems',
            'tab', 'search'
        ));
    }

    /**
     * Detail satu item Cash Shop
     */
    public function showCashItem(int $id)
    {
        $item = SealShopItem::findOrFail($id);
        return view('pages.marketplace_detail', compact('item'));
    }

    /**
     * Proses pembelian item Cash Shop
     * Player harus login (game account terhubung ke website)
     */
    public function buyCashItem(Request $request, int $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('warning', 'Login dulu untuk membeli item!');
        }

        $item = SealShopItem::findOrFail($id);
        $user = auth()->user();

        // Cek apakah user sudah link game account
        if (empty($user->game_id)) {
            return back()->with('error', 'Kamu belum menghubungkan akun game. Pergi ke Profil → Link Game Account.');
        }

        if ($item->isOutOfStock()) {
            return back()->with('error', 'Stok item habis!');
        }

        // Cek saldo Diamond player
        if ($user->diamonds < $item->item_price) {
            return back()->with('error', "Diamond tidak cukup! Kamu punya {$user->diamonds} Diamond, item ini seharga {$item->item_price} Diamond.");
        }

        DB::beginTransaction();
        try {
            // 1. Potong Diamond dari website user
            $user->decrement('diamonds', $item->item_price);

            // 2. Tambah item ke inventory game
            // ⚠️ SESUAIKAN: pastikan kolom inventory sesuai struktur DB kamu
            DB::connection('seal_game')->table('inventory')->insert([
                'char_name' => $user->game_id,   // ⚠️ SESUAIKAN kolom link karakter
                'item_id'   => $item->item_id,
                // Tambah kolom lain sesuai struktur inventory Seal Online kamu
            ]);

            // 3. Update buy count di cash shop
            $item->increment('item_buy_count');

            DB::commit();

            return back()->with('success', "Item \"{$item->memo}\" berhasil dibeli! Cek inventory di game.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Pembelian gagal. Coba lagi. Error: ' . $e->getMessage());
        }
    }
}
