<?php

namespace App\Models\Seal;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel vending_save di database gdb0101
 * Ini adalah Personal Shop / Player-to-Player Marketplace
 *
 * ⚠️ PERLU VERIFIKASI: Buka gdb0101 → vending_save di Navicat
 *    dan sesuaikan nama kolom di bawah
 */
class SealVending extends Model
{
    protected $connection = 'seal_game';
    protected $table      = 'vending_save';
    public    $timestamps = false;

    /**
     * Ambil semua item yang sedang dijual player
     */
    public static function getActiveListings(): \Illuminate\Support\Collection
    {
        return self::on('seal_game')
            ->orderBy('num_idx', 'desc') // ⚠️ SESUAIKAN kolom sort
            ->get();
    }

    /**
     * Ambil listing milik satu player
     */
    public static function getByCharacter(string $charName): \Illuminate\Support\Collection
    {
        return self::on('seal_game')
            ->where('char_name', $charName) // ⚠️ SESUAIKAN kolom
            ->get();
    }

    public function getImagePathAttribute(): string
    {
        $itemId     = $this->item_id ?? $this->itemid ?? 0;
        $customPath = public_path("images/items/{$itemId}.png");
        if (file_exists($customPath)) {
            return "/images/items/{$itemId}.png";
        }
        return "/images/items/default.png";
    }
}
