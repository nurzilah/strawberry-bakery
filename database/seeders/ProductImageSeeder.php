<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mapping = [
            // Roti Manis & Gurih
            "Roti Banana Split" => "https://images.unsplash.com/photo-1603046891744-1f608eef1b5a?w=500",
            "Roti Chicken Cheese" => "https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=500",
            "Roti Long Coklat Keju" => "https://images.unsplash.com/photo-1549931319-a545dcf3bc7b?w=500",
            "Roti Pandan Chiffon" => "https://images.unsplash.com/photo-1509440159596-0249088772ff?w=500",
            "Roti Pisang Banana Split" => "https://images.unsplash.com/photo-1603046891744-1f608eef1b5a?w=500",
            "Roti Coklat Keju" => "https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=500",
            "Paket 7 Rasa" => "https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=500",
            "Pizza Ayam" => "https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=500",
            "Rainbow Wool Bread" => "https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=500",
            "Cream Cheese Coklat" => "https://images.unsplash.com/photo-1558303489-c0e0f1daf4e4?w=500",
            "Cream Cheese Blue" => "https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=500",
            "Sobek Coklat Keju" => "https://images.unsplash.com/photo-1549931319-a545dcf3bc7b?w=500",
            "Sobek Strawberry" => "https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=500",

            // Cake & Tart
            "Brownies Slice" => "https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=500",
            "Brownies Mika" => "https://images.unsplash.com/photo-1515037893149-de7f840978e2?w=500",
            "Red Velvet Gulung" => "https://images.unsplash.com/photo-1586788680434-30d324b2d46f?w=500",
            "Black Forest Slice" => "https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500",
            "Shifon Keju" => "https://images.unsplash.com/photo-1562440499-64b9a2a4d0b4?w=500",
            "Cake Redvelvet" => "https://images.unsplash.com/photo-1616541823729-00fe0aacd32c?w=500",
            "Tart Mini Bento" => "https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=500",
            "Tart Diameter 14" => "https://images.unsplash.com/photo-1535141192574-5d4897c12636?w=500",
            "Bento Cake Custom" => "https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=500",
            "Kue Tart Black Forest" => "https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500",
            "Red Velvet Slice Cake" => "https://images.unsplash.com/photo-1616541823729-00fe0aacd32c?w=500",

            // Pastry & Donat
            "Donat Coklat Cerry" => "https://images.unsplash.com/photo-1551024601-bec78aea704b?w=500",
            "Donat Oreo" => "https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?w=500",
            "Donat Meses Keju" => "https://images.unsplash.com/photo-1583182332473-b31ba0576c7d?w=500",
            "Donat Tiramisu" => "https://images.unsplash.com/photo-1625227898296-f9c75e89e4d3?w=500",
            "Donat Satuan Meses" => "https://images.unsplash.com/photo-1551024601-bec78aea704b?w=500",
            "Premium Croissant Mentega" => "https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=500",
            "Roti Karakter Kura-Kura" => "https://images.unsplash.com/photo-1509440159596-0249088772ff?w=500",
        ];

        $defaultUrl = "https://images.unsplash.com/photo-1509440159596-0249088772ff?w=500";
        
        // Setup konteks dengan User-Agent supaya tidak diblokir
        $context = stream_context_create([
            'http' => [
                'header' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            ]
        ]);

        // Buat folder products di storage jika belum ada
        if (!Storage::disk('public')->exists('products')) {
            Storage::disk('public')->makeDirectory('products');
        }

        // Ambil semua produk yang unik berdasarkan nama
        $products = Product::select('name')->distinct()->get();

        foreach ($products as $product) {
            $name = $product->name;
            $url = $mapping[$name] ?? $defaultUrl;
            $filename = 'products/' . Str::slug($name) . '.jpg';
            
            try {
                $imageContent = @file_get_contents($url, false, $context);
                
                if ($imageContent) {
                    Storage::disk('public')->put($filename, $imageContent);
                    // Update semua produk yang bernama sama
                    Product::where('name', $name)->update(['image' => $filename]);
                    $this->command->info("Berhasil download & update: {$name}");
                } else {
                    $this->command->error("Gagal download image dari URL untuk: {$name}");
                }
            } catch (\Exception $e) {
                $this->command->error("Exception saat proses {$name}: " . $e->getMessage());
            }
        }
        
        $this->command->info("Seeding foto produk selesai!");
    }
}
