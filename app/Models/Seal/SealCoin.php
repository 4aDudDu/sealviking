<?php

namespace App\Models\Seal;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel coin di database gdb0101
 * Ini adalah data mata uang player (Cegel, Diamond, dll)
 *
 * ⚠️ PERLU VERIFIKASI kolom di Navicat:
 * Buka gdb0101 → tabel coin → lihat kolom-kolomnya
 * Sesuaikan nama kolom di bawah
 */
class SealCoin extends Model
{
    protected $connection = 'seal_game';
    protected $table      = 'coin';
    protected $primaryKey = 'id'; // ⚠️ SESUAIKAN: mungkin 'char_name' atau 'charid'
    public    $keyType    = 'string';
    public    $timestamps = false;

    /**
     * ⚠️ SESUAIKAN: Ganti nama kolom sesuai yang ada di tabel coin kamu
     * Contoh kolom umum di Seal Online:
     * - 'coin'    : Diamond / Cash Point
     * - 'cegel'   : Cegel (mata uang in-game)
     * - 'gcoin'   : Gold Coin
     * - 'mcoin'   : Marble Coin
     */
    protected $fillable = [
        'id',
        'coin',   // ⚠️ SESUAIKAN
        'cegel',  // ⚠️ SESUAIKAN
    ];

    /**
     * Ambil data coin berdasarkan account ID
     */
    public static function getByAccountId(string $accountId): ?self
    {
        return self::on('seal_game')->where('id', $accountId)->first(); // ⚠️ SESUAIKAN kolom 'id'
    }

    /**
     * Format Cegel dengan pemisah ribuan
     */
    public function getFormattedCegelAttribute(): string
    {
        return number_format($this->cegel ?? 0); // ⚠️ SESUAIKAN nama kolom
    }

    /**
     * Format Coin/Diamond
     */
    public function getFormattedCoinAttribute(): string
    {
        return number_format($this->coin ?? 0); // ⚠️ SESUAIKAN nama kolom
    }
}
