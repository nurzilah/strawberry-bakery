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

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
}
