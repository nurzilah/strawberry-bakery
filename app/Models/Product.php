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
        'purchase_count', 'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'float',
        'purchase_count' => 'integer',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_promo' => 'boolean',
        'is_available' => 'boolean',
        'is_active' => 'boolean',
        'pickup_date' => 'date'
    ];

    /**
     * Dapatkan URL gambar produk (via /uploads/products/ tanpa storage:link).
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) return null;

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        $filename = str_replace('products/', '', $this->image);

        return url('uploads/products/' . $filename);
    }

    /**
     * Dapatkan URL gambar atau fallback default logo.
     */
    public function getImageDefaultAttribute(): string
    {
        return $this->image_url ?? asset('images/logo.png');
    }

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
        $stockBefore = $this->stock ?? 0;
        $stockAfter = $stockBefore + $quantity;

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

    public function hasNoStock(): bool
    {
        return $this->stock !== null && $this->stock <= 0;
    }

    public function hasUnlimitedStock(): bool
    {
        return $this->stock === null;
    }

    public function hasStockTracking(): bool
    {
        return $this->stock !== null;
    }

    public function canBeSold(): bool
    {
        if (!$this->is_active) return false;
        if ($this->stock !== null && $this->stock <= 0) return false;
        return true;
    }

    public function hasSufficientStock(int $quantity): bool
    {
        if (!$this->hasStockTracking()) return true;
        return $this->stock >= $quantity;
    }
}
