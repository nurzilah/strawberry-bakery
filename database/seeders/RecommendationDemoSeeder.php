<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Data demo untuk visualisasi User-Based Collaborative Filtering (KNN).
 *
 * Setiap pelanggan mempunyai sebagian produk yang sama dengan pelanggan dalam
 * profilnya, sekaligus satu-dua produk berbeda. Pola tersebut membuat cosine
 * similarity, nearest neighbors, dan produk hasil prediksi mudah terlihat di UI.
 */
class RecommendationDemoSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::query()->get()->keyBy('slug');

        if ($products->count() < 8) {
            $this->command?->warn('RecommendationDemoSeeder memerlukan ProductSeeder terlebih dahulu.');

            return;
        }

        $profiles = [
            // Pelanggan roti manis: produk coklat/keju dan paket keluarga.
            ['Ayu Pratama', 'ayu.pratama', ['cream-cheese-coklat' => 2, 'roti-coklat-keju' => 3, 'sobek-coklat-keju' => 1, 'paket-7-rasa' => 1]],
            ['Bagas Saputra', 'bagas.saputra', ['cream-cheese-coklat' => 1, 'roti-coklat-keju' => 2, 'sobek-strawberry' => 1, 'pizza-ayam' => 1]],
            ['Citra Lestari', 'citra.lestari', ['cream-cheese-blue' => 2, 'roti-coklat-keju' => 2, 'sobek-coklat-keju' => 1, 'paket-7-rasa' => 2]],
            ['Dimas Ramadhan', 'dimas.ramadhan', ['cream-cheese-coklat' => 2, 'roti-pisang-banana-split' => 2, 'pizza-ayam' => 1, 'paket-7-rasa' => 1]],
            // Pelanggan cake: potongan cake, cake gulung, dan tart.
            ['Eka Wulandari', 'eka.wulandari', ['brownies-slice' => 3, 'black-forest-slice' => 1, 'cake-redvelvet' => 1, 'tart-mini-bento' => 1]],
            ['Farhan Maulana', 'farhan.maulana', ['brownies-slice' => 2, 'brownies-mika' => 1, 'red-velvet-gulung' => 1, 'cake-redvelvet' => 2]],
            ['Gita Maharani', 'gita.maharani', ['brownies-slice' => 2, 'black-forest-slice' => 2, 'shifon-keju' => 1, 'tart-mini-bento' => 1]],
            ['Hendra Wijaya', 'hendra.wijaya', ['brownies-mika' => 1, 'red-velvet-gulung' => 1, 'cake-redvelvet' => 2, 'tart-diameter-14' => 1]],
            // Pelanggan donat/pastry: pembelian camilan dalam jumlah lebih banyak.
            ['Intan Permata', 'intan.permata', ['donat-coklat-cerry' => 3, 'donat-oreo' => 2, 'donat-meses-keju' => 2, 'donat-tiramisu' => 1]],
            ['Joko Santoso', 'joko.santoso', ['donat-coklat-cerry' => 2, 'donat-oreo' => 3, 'donat-tiramisu' => 2, 'brownies-slice' => 1]],
            ['Kirana Putri', 'kirana.putri', ['donat-oreo' => 2, 'donat-meses-keju' => 3, 'donat-tiramisu' => 2, 'cream-cheese-coklat' => 1]],
            ['Lukman Hakim', 'lukman.hakim', ['donat-coklat-cerry' => 2, 'donat-meses-keju' => 2, 'donat-tiramisu' => 3, 'roti-coklat-keju' => 1]],
        ];

        $users = [];
        foreach ($profiles as $index => [$name, $emailPrefix]) {
            $users[] = User::updateOrCreate(
                ['email' => $emailPrefix . '@demo.strawberrybakery.test'],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'phone' => '0812' . str_pad((string) (700000 + $index), 8, '0', STR_PAD_LEFT),
                    'address' => 'Alamat demo visualisasi KNN, Kota Tegal',
                    'city' => 'Kota Tegal',
                ]
            );
        }

        DB::transaction(function () use ($users, $profiles, $products): void {
            $userIds = collect($users)->pluck('id');
            $orderIds = Order::query()->whereIn('order_number', $this->demoOrderNumbers())->pluck('id');

            // Hapus hanya catatan yang dibuat oleh seeder ini agar aman dijalankan berulang.
            DB::table('recommendation_logs')->whereIn('user_id', $userIds)->delete();
            DB::table('product_reviews')->whereIn('user_id', $userIds)->delete();
            DB::table('order_items')->whereIn('order_id', $orderIds)->delete();
            Order::query()->whereIn('id', $orderIds)->delete();

            foreach ($profiles as $userIndex => [$name, $emailPrefix, $preferences]) {
                $user = $users[$userIndex];
                $preferenceRows = collect($preferences)
                    ->map(fn (int $quantity, string $slug) => ['product' => $products->get($slug), 'quantity' => $quantity])
                    ->filter(fn (array $row) => $row['product'] !== null)
                    ->values();

                // Dua order pada hari berbeda membuat riwayat terasa seperti transaksi nyata.
                foreach ($preferenceRows->chunk(2) as $orderIndex => $items) {
                    $orderedAt = Carbon::now()->subDays(52 - (($userIndex * 3) + ($orderIndex * 9)));
                    $total = $items->sum(fn (array $item) => (int) $item['product']->price * $item['quantity']);
                    $order = Order::create([
                        'user_id' => $user->id,
                        'order_number' => sprintf('DEMO-KNN-%02d-%02d', $userIndex + 1, $orderIndex + 1),
                        'total_amount' => $total,
                        'status' => 'completed',
                        'payment_method' => $orderIndex % 2 === 0 ? 'qris' : 'transfer',
                        'payment_status' => 'paid',
                        'payment_type' => $orderIndex % 2 === 0 ? 'qris' : 'bank_transfer',
                        'paid_at' => $orderedAt->copy()->addMinutes(10),
                        'pickup_date' => $orderedAt->copy()->addDay()->toDateString(),
                        'created_at' => $orderedAt,
                        'updated_at' => $orderedAt,
                    ]);

                    foreach ($items as $item) {
                        $product = $item['product'];
                        DB::table('order_items')->insert([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'quantity' => $item['quantity'],
                            'price' => (int) $product->price,
                            'subtotal' => (int) $product->price * $item['quantity'],
                            'created_at' => $orderedAt,
                            'updated_at' => $orderedAt,
                        ]);

                        DB::table('product_reviews')->insert([
                            'user_id' => $user->id,
                            'product_id' => $product->id,
                            'order_id' => $order->id,
                            'rating' => $this->ratingFor($userIndex, $orderIndex),
                            'comment' => 'Pembelian demo untuk visualisasi rekomendasi KNN.',
                            'is_approved' => true,
                            'created_at' => $orderedAt->copy()->addDays(2),
                            'updated_at' => $orderedAt->copy()->addDays(2),
                        ]);
                    }
                }
            }

            // Log memberi angka precision/recall yang tidak kosong di halaman evaluasi.
            foreach ($users as $userIndex => $user) {
                $candidate = $products->values()[$userIndex % $products->count()];
                DB::table('recommendation_logs')->insert([
                    'user_id' => $user->id,
                    'product_id' => $candidate->id,
                    'recommended_at' => Carbon::now()->subDays($userIndex + 1),
                    'clicked' => $userIndex % 4 !== 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            Product::query()->each(function (Product $product): void {
                $purchaseCount = DB::table('order_items')
                    ->join('orders', 'orders.id', '=', 'order_items.order_id')
                    ->where('orders.status', 'completed')
                    ->where('order_items.product_id', $product->id)
                    ->sum('order_items.quantity');

                $product->update(['purchase_count' => $purchaseCount]);
            });
        });

        $this->command?->info('Data demo KNN selesai: 12 pelanggan, 24 transaksi completed, ulasan, dan log rekomendasi.');
    }

    private function demoOrderNumbers(): array
    {
        $numbers = [];
        for ($user = 1; $user <= 12; $user++) {
            for ($order = 1; $order <= 2; $order++) {
                $numbers[] = sprintf('DEMO-KNN-%02d-%02d', $user, $order);
            }
        }

        return $numbers;
    }

    private function ratingFor(int $userIndex, int $orderIndex): int
    {
        return [5, 4, 5, 4, 5, 4][$userIndex % 6] - ($orderIndex % 2);
    }
}
