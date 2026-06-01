<?php

namespace App\Http\Controllers;

use App\Models\Seal\SealAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->intended('/home');
        }
        return view('pages.auth.login');
    }

    /**
     * Login player menggunakan ID akun game (dari seal_member.idtable1)
     * Admin login tetap terpisah via Filament (/admin/login)
     */
    public function login(Request $request)
    {
        $request->validate([
            'game_id'  => ['required', 'string', 'max:50'],
            'password' => ['required', 'string'],
        ], [
            'game_id.required'  => 'ID akun game wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $gameId   = strtolower(trim($request->game_id));
        $password = $request->password;

        // --- Verifikasi ke database Seal Online ---
        $gameAccount = null;
        try {
            $gameAccount = SealAccount::findAccountAcrossTables($gameId);
        } catch (\Exception $e) {
            // Game DB belum terkonek — fallback ke user lokal
            $localUser = User::where('game_id', $gameId)->first();
            if ($localUser && Hash::check($password, $localUser->password)) {
                Auth::login($localUser, $request->has('remember'));
                $request->session()->regenerate();
                return redirect()->intended('/home')
                    ->with('success', 'Selamat datang, ' . $localUser->name . '!');
            }
            return back()->withErrors([
                'game_id' => 'Server game tidak dapat dihubungi. Coba lagi nanti.',
            ])->withInput($request->only('game_id'));
        }

        // Akun tidak ditemukan di game DB
        if (!$gameAccount) {
            return back()->withErrors([
                'game_id' => 'Akun game tidak ditemukan. Periksa ID kamu.',
            ])->withInput($request->only('game_id'));
        }

        // Verifikasi password (OLD_PASSWORD — standar Seal Online)
        if (!$gameAccount->verifyPassword($password)) {
            // DEBUG SEMENTARA — hapus setelah berhasil!
            $storedHash = strtolower(trim($gameAccount->passed));
            $computedHash = strtolower(\App\Models\Seal\SealAccount::mysqlOldPassword($password));
            return back()->withErrors([
                'game_id' => "ID atau password salah. [DEBUG] stored=$storedHash | computed=$computedHash | input=$password",
            ])->withInput($request->only('game_id'));
        }

        // Cek block
        if ($gameAccount->isBlocked()) {
            return back()->withErrors([
                'game_id' => 'Akun kamu sedang di-block oleh GM. Hubungi support.',
            ])->withInput($request->only('game_id'));
        }

        // --- Cari atau buat user website yang linked ke game account ---
        $user = User::where('game_id', $gameId)->first();

        if (!$user) {
            // Buat user website baru, otomatis linked ke game account
            $user = User::create([
                'name'      => $gameAccount->char_name ?? $gameId,
                'email'     => $gameId . '@seal.local',       // Email internal (tidak dipakai)
                'password'  => Hash::make($password),          // Simpan hash lokal sebagai backup
                'game_id'   => $gameId,
                'char_name' => $gameAccount->char_name ?? $gameId,
                'coins'     => 0,
                'diamonds'  => 0,
            ]);
        } else {
            // Update char_name jika berubah di game
            $user->update([
                'char_name' => $gameAccount->char_name ?? $user->char_name,
                'name'      => $gameAccount->char_name ?? $user->name,
            ]);
        }

        Auth::login($user, $request->has('remember'));
        $request->session()->regenerate();

        return redirect()->intended('/home')
            ->with('success', '⚔️ Welcome back, ' . ($gameAccount->char_name ?? $gameId) . '!');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/home')->with('success', 'Kamu berhasil logout. Sampai jumpa di Valhalla!');
    }
}
