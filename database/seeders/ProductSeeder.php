<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'roti-manis-gurih' => Category::firstOrCreate(
                ['slug' => 'roti-manis-gurih'],
                [
                    'name' => 'Roti Manis & Gurih',
                    'description' => 'Pilihan roti manis dan gurih khas Strawberry Bakery.',
                    'icon' => 'bread-slice',
                    'is_active' => true,
                ]
            ),
            'cake-tart' => Category::firstOrCreate(
                ['slug' => 'cake-tart'],
                [
                    'name' => 'Cake & Tart',
                    'description' => 'Aneka cake dan tart untuk hadiah, acara, dan pesanan spesial.',
                    'icon' => 'cake',
                    'is_active' => true,
                ]
            ),
            'pastry-donat' => Category::firstOrCreate(
                ['slug' => 'pastry-donat'],
                [
                    'name' => 'Pastry & Donat',
                    'description' => 'Produk pastry renyah dan donat lembut siap santap.',
                    'icon' => 'croissant',
                    'is_active' => true,
                ]
            ),
        ];

        $products = [
            [
                'category_id' => $categories['roti-manis-gurih']->id,
                'name' => 'Roti Banana Split',
                'price' => 10500,
                'stock' => 30,
                'description' => 'Roti lembut dengan perpaduan rasa pisang manis khas Strawberry Bakery.',
            ],
            [
                'category_id' => $categories['roti-manis-gurih']->id,
                'name' => 'Roti Chicken Cheese',
                'price' => 10500,
                'stock' => 25,
                'description' => 'Roti gurih dengan topping daging ayam cincang bumbu spesial dan taburan keju melimpah.',
            ],
            [
                'category_id' => $categories['roti-manis-gurih']->id,
                'name' => 'Roti Long Coklat Keju',
                'price' => 23000,
                'stock' => 15,
                'description' => 'Roti ukuran panjang dengan kombinasi toping meses coklat premium dan parutan keju gurih.',
            ],
            [
                'category_id' => $categories['roti-manis-gurih']->id,
                'name' => 'Roti Pandan Chiffon',
                'price' => 29000,
                'stock' => 10,
                'description' => 'Kue chiffon pandan super lembut, wangi aroma pandan alami.',
            ],
            [
                'category_id' => $categories['roti-manis-gurih']->id,
                'name' => 'Rainbow Wool Bread',
                'price' => 34000,
                'stock' => 12,
                'description' => 'Roti hits bertekstur serat rajutan wol dengan tampilan warna-warni pelangi.',
            ],
            [
                'category_id' => $categories['roti-manis-gurih']->id,
                'name' => 'Roti Karakter Kura-Kura',
                'price' => 11000,
                'stock' => 20,
                'description' => 'Roti manis berbentuk karakter kura-kura lucu yang sangat disukai anak-anak.',
            ],
            [
                'category_id' => $categories['cake-tart']->id,
                'name' => 'Bento Cake Custom',
                'price' => 40000,
                'stock' => 8,
                'description' => 'Kue tart mini ala Korea berukuran personal, bebas custom tulisan untuk hadiah.',
            ],
            [
                'category_id' => $categories['cake-tart']->id,
                'name' => 'Kue Tart Black Forest Ukuran 14',
                'price' => 45000,
                'stock' => 5,
                'description' => 'Kue tart basic rasa coklat Black Forest klasik dengan hiasan buah ceri segar.',
            ],
            [
                'category_id' => $categories['cake-tart']->id,
                'name' => 'Red Velvet Slice Cake',
                'price' => 27000,
                'stock' => 15,
                'description' => 'Potongan kue red velvet mewah dengan lapisan cream cheese premium.',
            ],
            [
                'category_id' => $categories['pastry-donat']->id,
                'name' => 'Premium Croissant Mentega',
                'price' => 15000,
                'stock' => 15,
                'description' => 'Pastry khas Prancis bertekstur renyah di luar dengan aroma butter kuat.',
            ],
            [
                'category_id' => $categories['pastry-donat']->id,
                'name' => 'Donat Satuan Meses',
                'price' => 10000,
                'stock' => 40,
                'description' => 'Donat kentang empuk dengan balutan mentega dan taburan meses coklat tebal.',
            ],
        ];

        foreach ($products as $item) {
            $slug = Str::slug($item['name']);

            Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $item['category_id'],
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                    'image' => $slug . '.jpg',
                    'is_available' => true,
                    'purchase_count' => rand(15, 70),
                    'rating' => rand(4, 5),
                    'reviews_count' => rand(5, 25),
                ]
            );
        }
    }
}
