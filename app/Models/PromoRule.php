<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoRule extends Model
{
    protected $fillable = [
        'name',
        'scope',
        'min_qty',
        'free_qty',
        'discount_percent',
        'free_ongkir_max_km',
        'bonus_item_name',
        'bonus_item_qty',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'bonus_item_qty' => 'integer',
    ];
}
