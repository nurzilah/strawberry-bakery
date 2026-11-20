<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Batas minimum item untuk metode Delivery
    |--------------------------------------------------------------------------
    */
    'delivery_min_qty' => env('DELIVERY_MIN_QTY', 12),

    /*
    |--------------------------------------------------------------------------
    | Titik Lokasi Toko & Aturan Ongkos Kirim (Delivery)
    |--------------------------------------------------------------------------
    | Strawberry Bakery - Jl. AR. Hakim No. 149, Mangkukusuman,
    | Kec. Tegal Timur, Kota Tegal
    |
    | base_shipping_cost    : Rp 10.000 untuk jarak <= 5 km
    | cost_per_km           : Rp 2.500 per km untuk jarak > 5 km
    | max_delivery_radius_km: 25 km (maks jangkauan: Tegal, Slawi, Brebes)
    | road_correction_factor: 1.3 (faktor koreksi jalan darat vs garis lurus)
    */
    'lat' => (float) env('STORE_LAT', -6.86985),
    'lng' => (float) env('STORE_LNG', 109.13615),
    'store_name' => 'Strawberry Bakery',
    'store_address' => 'Jl. AR. Hakim No. 149, Mangkukusuman, Kec. Tegal Timur, Kota Tegal',
    'base_shipping_cost' => (int) env('STORE_BASE_SHIPPING_COST', 10000),
    'cost_per_km' => (int) env('STORE_COST_PER_KM', 2500),
    'max_delivery_radius_km' => (float) env('STORE_MAX_DELIVERY_RADIUS_KM', 25.0),
    'road_correction_factor' => (float) env('STORE_ROAD_CORRECTION_FACTOR', 1.3),

    /*
    |--------------------------------------------------------------------------
    | Jam Operasional Toko
    |--------------------------------------------------------------------------
    | start : jam buka (format H:i, 24 jam)
    | end   : jam tutup (pesanan terakhir boleh dipilih paling lambat jam ini)
    | step  : interval antar pilihan jam dalam menit (misal 30 = setiap 30 menit)
    */
    'operating_hours' => [
        'start' => env('STORE_HOURS_START', '08:00'),
        'end'   => env('STORE_HOURS_END',   '20:00'),
        'step'  => (int) env('STORE_HOURS_STEP', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Durasi Deadline Pengambilan (Pickup)
    |--------------------------------------------------------------------------
    | Jumlah JAM setelah jam yang dipilih customer, di mana pesanan pickup
    | wajib sudah diambil. Lewat batas ini, toko tidak bertanggung jawab.
    */
    'pickup_deadline_hours' => (int) env('PICKUP_DEADLINE_HOURS', 2),

    /*
    |--------------------------------------------------------------------------
    | Buffer Waktu Pemesanan Hari Ini (Order Lead Time)
    |--------------------------------------------------------------------------
    | Buffer waktu minimal (dalam JAM) dari waktu sekarang jika pelanggan memilih
    | tanggal hari ini, agar toko memiliki waktu untuk mempersiapkan pesanan.
    */
    'order_lead_time_hours' => (int) env('ORDER_LEAD_TIME_HOURS', 1),
];
