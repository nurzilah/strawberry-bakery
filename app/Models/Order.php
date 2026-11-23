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

    /**
     * Estimasi lama perjalanan kurir motor dalam menit (kecepatan 30 km/jam).
     */
    public function getEstimatedTravelMinutesAttribute(): ?int
    {
        if ($this->delivery_method !== 'delivery' || empty($this->distance_km) || (float) $this->distance_km <= 0) {
            return null;
        }

        // Asumsi kecepatan motor 30 km/jam = 0.5 km/menit -> (jarak / 30) * 60 menit
        return (int) ceil(((float) $this->distance_km / 30.0) * 60);
    }

    /**
     * Waktu persiapan & packing pesanan di toko (dalam menit).
     */
    public function getPreparationMinutesAttribute(): int
    {
        return 15;
    }

    /**
     * Total waktu jeda yang dibutuhkan (persiapan toko + waktu tempuh perjalanan).
     */
    public function getTotalDeliveryLeadMinutesAttribute(): ?int
    {
        $travelMinutes = $this->estimated_travel_minutes;
        if ($travelMinutes === null) {
            return null;
        }

        return $travelMinutes + $this->preparation_minutes;
    }

    /**
     * Objek Carbon batas maksimal kurir harus berangkat dari toko.
     */
    public function getLatestDepartureDateTimeAttribute(): ?\Carbon\Carbon
    {
        if ($this->delivery_method !== 'delivery' || empty($this->requested_time)) {
            return null;
        }

        $leadMinutes = $this->total_delivery_lead_minutes;
        if ($leadMinutes === null) {
            return null;
        }

        $dateStr = $this->requested_date ? $this->requested_date->format('Y-m-d') : now()->format('Y-m-d');
        try {
            $target = \Carbon\Carbon::parse("{$dateStr} {$this->requested_time}");
            return $target->subMinutes($leadMinutes);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * String format batas jam berangkat kurir (misal: "09:20 WIB").
     */
    public function getLatestDepartureTimeAttribute(): ?string
    {
        $departure = $this->latest_departure_date_time;
        return $departure ? $departure->format('H:i') . ' WIB' : null;
    }

    /**
     * String format target jam tiba pesanan yang dipilih customer (misal: "10:00 WIB").
     */
    public function getFormattedTargetTimeAttribute(): string
    {
        if (empty($this->requested_time)) {
            return '-';
        }

        try {
            return \Carbon\Carbon::parse($this->requested_time)->format('H:i') . ' WIB';
        } catch (\Throwable $e) {
            return $this->requested_time . ' WIB';
        }
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
