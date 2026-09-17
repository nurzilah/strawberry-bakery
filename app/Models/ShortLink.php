<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortLink extends Model
{
    protected $fillable = ['code', 'target_url', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
