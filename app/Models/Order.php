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
        'requested_date',
        'requested_time',
        'pickup_deadline_at',
        'delivery_method',
        'courier_id',
        'courier_name',
        'courier_phone',
        'delivery_status',
        'delivered_at',
        'delivery_notes',
        'delivery_address',
        'delivery_lat',
        'delivery_lng',
        'distance_km',
        'shipping_cost',
        'voucher_code',
        'voucher_discount',
        'discount_amount',
        'free_qty_applied',
        'bonus_item_name',
        'bonus_item_qty',
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
        'shipping_cost' => 'integer',
        'delivery_lat' => 'float',
        'delivery_lng' => 'float',
        'distance_km' => 'float',
        'voucher_discount' => 'integer',
        'discount_amount' => 'integer',
        'free_qty_applied' => 'integer',
        'bonus_item_qty' => 'integer',
        'qris_expires_at' => 'datetime',
        'pickup_date' => 'date',
        'requested_date' => 'date',
        'pickup_deadline_at' => 'datetime',
        'paid_at' => 'datetime',
        'receipt_sent_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courier()
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    public function getCourierDisplayNameAttribute(): string
    {
        return $this->courier?->name ?? $this->courier_name ?? '-';
    }

    public function getCourierDisplayPhoneAttribute(): string
    {
        return $this->courier?->phone ?? $this->courier_phone ?? '-';
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function packages()
    {
        return $this->hasMany(OrderPackage::class);
    }
}
