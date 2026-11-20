<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPackageItem extends Model
{
    protected $fillable = [
        'order_package_id',
        'product_id',
        'quantity',
        'unit_price',
    ];

    public function package()
    {
        return $this->belongsTo(OrderPackage::class, 'order_package_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
