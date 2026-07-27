<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'quantity', 'detail_cart_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailCart()
    {
        return $this->belongsTo(DetailCart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getSubtotalAttribute()
    {
        $product = $this->detailCart?->product ?? $this->product;
        $quantity = $this->detailCart?->quantity ?? ($this->quantity ?? 0);

        return ($product?->price ?? 0) * $quantity;
    }

    public static function countItemsForUser(int $userId): int
    {
        if (Schema::hasColumn('carts', 'quantity')) {
            return (int) static::query()
                ->where('user_id', $userId)
                ->sum('quantity');
        }

        if (Schema::hasColumn('carts', 'detail_cart_id') && Schema::hasTable('detail_carts')) {
            return (int) static::query()
                ->where('user_id', $userId)
                ->join('detail_carts', 'detail_carts.id', '=', 'carts.detail_cart_id')
                ->sum('detail_carts.quantity');
        }

        return 0;
    }
}
