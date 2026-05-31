<?php

namespace App\Models\Seal;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel merchant_shop_cegel (Cegel Shop)
 * Item yang dibeli dengan Cegel (mata uang in-game)
 */
class SealCegelShopItem extends Model
{
    protected $connection = 'seal_shop';
    protected $table      = 'merchant_shop_cegel';
    protected $primaryKey = 'num_idx';
    public    $timestamps = false;

    protected $fillable = [
        'item_id', 'item_price', 'item_io', 'item_ioo',
        'item_limit_time', 'item_stock_limit', 'item_buy_count', 'memo',
    ];

    public function scopeAvailable($query)
    {
        return $query->where(function($q) {
            $q->where('item_stock_limit', 9999)
              ->orWhereRaw('item_buy_count < item_stock_limit');
        });
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where('memo', 'LIKE', "%{$keyword}%");
    }

    public function getRemainingStockAttribute(): string
    {
        if ($this->item_stock_limit >= 9999) return 'Unlimited';
        return max(0, $this->item_stock_limit - $this->item_buy_count);
    }

    public function isOutOfStock(): bool
    {
        if ($this->item_stock_limit >= 9999) return false;
        return ($this->item_buy_count >= $this->item_stock_limit);
    }

    public function getImagePathAttribute(): string
    {
        $customPath = public_path("images/items/{$this->item_id}.png");
        if (file_exists($customPath)) {
            return "/images/items/{$this->item_id}.png";
        }
        return "/images/items/default.png";
    }
}
