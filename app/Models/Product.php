<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price',
        'image', 'stock', 'rating', 'reviews_count', 'is_featured',
        'is_new', 'is_promo', 'is_available', 'pickup_date',
        'purchase_count'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'float',
        'purchase_count' => 'integer',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_promo' => 'boolean',
        'is_available' => 'boolean',
        'pickup_date' => 'date'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }

    public function addStock($quantity, $notes = null, $userId = null)
    {
        $stockBefore = $this->stock;
        $stockAfter = $this->stock + $quantity;

        $this->stock = $stockAfter;
        $this->save();

        $this->stockHistories()->create([
            'user_id' => $userId ?? auth()->id(),
            'quantity_added' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'notes' => $notes,
        ]);
    }
}
