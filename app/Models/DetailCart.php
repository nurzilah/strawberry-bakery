<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailCart extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'quantity'];

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
