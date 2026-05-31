<?php

namespace App\Models\Seal;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel inventory di database gdb0101
 *
 * ⚠️  PERLU VERIFIKASI: Sesuaikan nama kolom di bawah
 *     dengan struktur tabel inventory di Navicat kamu.
 *
 * Kolom yang umum di Seal Online:
 * - charid / char_name  : ID/nama karakter pemilik
 * - slot / slot_index   : nomor slot inventory
 * - item_id / itemid    : ID item
 * - item_count / count  : jumlah item
 */
class SealInventory extends Model
{
    protected $connection = 'seal_game';
    protected $table      = 'inventory';
    public    $timestamps = false;
    public    $incrementing = false;

    // ⚠️ SESUAIKAN: ganti 'char_name' kalau kolomnya beda (misal: 'charid')
    protected $primaryKey = 'char_name';
    public    $keyType    = 'string';

    /**
     * Ambil inventory berdasarkan nama karakter
     * ⚠️ SESUAIKAN: ganti 'char_name' sesuai kolom di tabelmu
     */
    public static function getByCharacter(string $charName): \Illuminate\Support\Collection
    {
        return self::on('seal_game')
            ->where('char_name', $charName) // ⚠️ SESUAIKAN kolom ini
            ->orderBy('slot', 'asc')        // ⚠️ SESUAIKAN kolom slot
            ->get();
    }

    /**
     * Path gambar item
     */
    public function getImagePathAttribute(): string
    {
        $itemId     = $this->item_id ?? $this->itemid ?? 0; // ⚠️ SESUAIKAN
        $customPath = public_path("images/items/{$itemId}.png");
        if (file_exists($customPath)) {
            return "/images/items/{$itemId}.png";
        }
        return "/images/items/default.png";
    }
}
