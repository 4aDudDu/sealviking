<?php

namespace App\Http\Controllers;

use App\Models\Seal\SealAccount;
use App\Models\Seal\SealInventory;
use App\Models\Seal\SealCoin;
use App\Models\Seal\SealVending;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerProfileController extends Controller
{

    /**
     * Halaman profil player — menampilkan data game
     */
    public function index()
    {
        $user       = auth()->user();
        $gameAccount = null;
        $coins       = null;
        $inventory   = collect();
        $store       = collect();

        // Ambil data hanya jika sudah link game account
        if (!empty($user->game_id)) {
            try {
                // Data akun game
                $gameAccount = SealAccount::on('seal_member')
                    ->where('id', $user->game_id)
                    ->first();

                // Mata uang player di game
                $coins = SealCoin::getByAccountId($user->game_id);

                // Inventory karakter
                $charName = $gameAccount->char_name ?? $user->game_id;
                $inventory = SealInventory::getByCharacter($charName);

                // Storage/Warehouse (tabel store)
                // ⚠️ SESUAIKAN: ganti 'char_name' dengan kolom link yang benar
                $store = DB::connection('seal_game')
                    ->table('store')
                    ->where('char_name', $charName) // ⚠️ SESUAIKAN
                    ->get();

            } catch (\Exception $e) {
                session()->flash('db_error', 'Tidak dapat memuat data game: ' . $e->getMessage());
            }
        }

        return view('pages.profile', compact(
            'user', 'gameAccount', 'coins', 'inventory', 'store'
        ));
    }

    /**
     * Form link game account ke website account
     */
    public function linkGameAccount(Request $request)
    {
        $request->validate([
            'game_id'       => 'required|string|max:50',
            'game_password' => 'required|string',
        ]);

        $gameId = strtolower(trim($request->game_id));

        // Cari akun di semua tabel idtable1-5
        $gameAccount = SealAccount::findAccountAcrossTables($gameId);

        if (!$gameAccount) {
            return back()->with('error', 'Akun game tidak ditemukan. Pastikan ID sudah benar.');
        }

        // Verifikasi password
        if (!$gameAccount->verifyPassword($request->game_password)) {
            return back()->with('error', 'Password game salah!');
        }

        // Cek apakah game account sudah dipakai user lain
        $existingLink = User::where('game_id', $gameId)
            ->where('id', '!=', auth()->id())
            ->first();

        if ($existingLink) {
            return back()->with('error', 'Akun game ini sudah dihubungkan dengan akun website lain.');
        }

        if ($gameAccount->isBlocked()) {
            return back()->with('error', 'Akun game kamu sedang di-block. Hubungi GM.');
        }

        // Simpan link
        auth()->user()->update([
            'game_id'   => $gameId,
            'char_name' => $gameAccount->char_name,
        ]);

        return back()->with('success', "Akun game \"{$gameAccount->char_name}\" berhasil dihubungkan!");
    }

    /**
     * Lepas link game account
     */
    public function unlinkGameAccount()
    {
        auth()->user()->update([
            'game_id'   => null,
            'char_name' => null,
        ]);

        return back()->with('success', 'Akun game berhasil dilepas dari website.');
    }
}
