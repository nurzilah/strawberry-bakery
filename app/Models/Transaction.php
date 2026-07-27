<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_number',
        'user_id',
        'subtotal',
        'total',
        'discount',
        'tax_rate',
        'tax_amount',
        'extra_charge',
        'extra_charge_label',
        'rounding',
        'grand_total',
        'payment_method',
        'amount_paid',
        'amount_change',
        'notes',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'extra_charge' => 'decimal:2',
        'rounding' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'amount_change' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}