<?php

namespace App\Models\Seal;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel merchant_shop_cash di database nop_config
 * Ini adalah item Cash Shop yang dijual server kepada player
 *
 * Kolom:
 * - num_idx        : primary key
 * - item_id        : ID item di game
 * - item_price     : harga item (dalam Diamond/Cash Point)
 * - item_io        : jumlah item per pembelian
 * - item_ioo       : (spare)
 * - item_limit_time: batas waktu item (0 = permanent)
 * - item_date_limit: tanggal kadaluarsa item
 * - item_stock_limit: stok maksimal (9999 = unlimited)
 * - item_buy_count : berapa kali sudah dibeli
 * - memo           : nama item
 */
class SealShopItem extends Model
{
    protected $connection = 'seal_shop';
    protected $table      = 'merchant_shop_cash';
    protected $primaryKey = 'num_idx';
    public    $timestamps = false;

    protected $fillable = [
        'item_id', 'item_price', 'item_io', 'item_ioo',
        'item_limit_time', 'item_date_limit',
        'item_stock_limit', 'item_buy_count', 'memo',
    ];

    /**
     * Scope: hanya item yang masih tersedia (stok > 0 atau unlimited)
     */
    public function scopeAvailable($query)
    {
        return $query->where(function($q) {
            $q->where('item_stock_limit', 9999)
              ->orWhereRaw('item_buy_count < item_stock_limit');
        });
    }

    /**
     * Scope: filter berdasarkan keyword nama item
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where('memo', 'LIKE', "%{$keyword}%");
    }

    /**
     * Hitung sisa stok
     */
    public function getRemainingStockAttribute(): string
    {
        if ($this->item_stock_limit >= 9999) return 'Unlimited';
        return max(0, $this->item_stock_limit - $this->item_buy_count);
    }

    /**
     * Cek apakah item habis
     */
    public function isOutOfStock(): bool
    {
        if ($this->item_stock_limit >= 9999) return false;
        return ($this->item_buy_count >= $this->item_stock_limit);
    }

    /**
     * Path gambar item — cek dulu apakah ada di public/images/items/
     * Kalau tidak ada, pakai default placeholder
     */
    public function getImagePathAttribute(): string
    {
        $customPath = public_path("images/items/{$this->item_id}.png");
        if (file_exists($customPath)) {
            return "/images/items/{$this->item_id}.png";
        }
        return "/images/items/default.png";
    }
}
