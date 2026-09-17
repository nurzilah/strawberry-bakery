<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'payment_method',
        'payment_proof_path',
        'qris_expires_at',
        'pickup_date',
        'payment_status',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'payment_type',
        'paid_at',
        'receipt_sent_at',
        'snap_token',
    ];

    protected $casts = [
        'total_amount' => 'integer',
        'qris_expires_at' => 'datetime',
        'pickup_date' => 'date',
        'paid_at' => 'datetime',
        'receipt_sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
