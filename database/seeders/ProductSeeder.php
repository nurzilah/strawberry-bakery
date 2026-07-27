<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $catRoti = Category::firstOrCreate(['slug' => 'roti-manis-gurih'], ['name' => 'Roti Manis & Gurih', 'is_active' => true]);
        $catCake = Category::firstOrCreate(['slug' => 'cake-tart'], ['name' => 'Cake & Tart', 'is_active' => true]);
        $catPastry = Category::firstOrCreate(['slug' => 'pastry-donat'], ['name' => 'Pastry & Donat', 'is_active' => true]);

        $products = [
            // Roti Manis & Gurih
            [
                'name' => 'Cream Cheese Coklat',
                'category_id' => $catRoti->id,
                'price' => 11000,
                'stock' => 30,
                'description' => 'Roti manis dengan perpaduan isian cream cheese yang gurih lumer dan lapisan coklat lezat.',
                'is_featured' => true,
                'is_new' => false,
            ],
            [
                'name' => 'Cream Cheese Blue',
                'category_id' => $catRoti->id,
                'price' => 11000,
                'stock' => 30,
                'description' => 'Sensasi roti manis isi cream cheese yang nikmat dipadukan dengan selai blueberry segar.',
                'is_featured' => false,
                'is_new' => false,
            ],
            [
                'name' => 'Sobek Coklat Keju',
                'category_id' => $catRoti->id,
                'price' => 27000,
                'stock' => 20,
                'description' => 'Roti sobek super empuk yang pas untuk berbagi dengan taburan coklat dan keju melimpah.',
                'is_featured' => false,
                'is_new' => false,
            ],
            [
                'name' => 'Sobek Strawberry',
                'category_id' => $catRoti->id,
                'price' => 23000,
                'stock' => 20,
                'description' => 'Roti sobek lembut dengan balutan selai stroberi manis asam yang menyegarkan.',
                'is_featured' => false,
                'is_new' => false,
            ],
            [
                'name' => 'Roti Pisang Banana Split',
                'category_id' => $catRoti->id,
                'price' => 10500,
                'stock' => 25,
                'description' => 'Kombinasi klasik pisang manis berbalut adonan roti lembut khas cita rasa banana split.',
                'is_featured' => false,
                'is_new' => false,
            ],
            [
                'name' => 'Roti Coklat Keju',
                'category_id' => $catRoti->id,
                'price' => 10000,
                'stock' => 25,
                'description' => 'Favorit sepanjang masa, roti empuk perpaduan coklat lumer dan parutan keju.',
                'is_featured' => false,
                'is_new' => false,
            ],
            [
                'name' => 'Paket 7 Rasa',
                'category_id' => $catRoti->id,
                'price' => 22000,
                'stock' => 15,
                'description' => 'Beragam kenikmatan dalam satu kemasan hemat dengan tujuh varian rasa andalan.',
                'is_featured' => false,
                'is_new' => false,
            ],
            [
                'name' => 'Pizza Ayam',
                'category_id' => $catRoti->id,
                'price' => 35000,
                'stock' => 10,
                'description' => 'Roti gurih ala pizza dengan topping ayam cincang bumbu spesial, saus tomat, dan keju leleh.',
                'is_featured' => false,
                'is_new' => false,
            ],

            // Cake & Tart
            [
                'name' => 'Brownies Slice',
                'category_id' => $catCake->id,
                'price' => 8500,
                'stock' => 20,
                'description' => 'Potongan kecil kebahagiaan dari brownies panggang fudgy yang super nyoklat di lidah.',
                'is_featured' => true,
                'is_new' => false,
            ],
            [
                'name' => 'Brownies Mika',
                'category_id' => $catCake->id,
                'price' => 31000,
                'stock' => 10,
                'description' => 'Satu mika penuh brownies fudgy berpadu topping aneka rasa yang cocok untuk hadiah.',
                'is_featured' => false,
                'is_new' => false,
            ],
            [
                'name' => 'Red Velvet Gulung',
                'category_id' => $catCake->id,
                'price' => 35000,
                'stock' => 10,
                'description' => 'Roll cake istimewa red velvet dengan sentuhan cream cheese manis nan legit.',
                'is_featured' => false,
                'is_new' => true,
            ],
            [
                'name' => 'Black Forest Slice',
                'category_id' => $catCake->id,
                'price' => 20000,
                'stock' => 15,
                'description' => 'Irisan cake black forest lembut yang berpadu dengan whipped cream dan buah ceri.',
                'is_featured' => false,
                'is_new' => false,
            ],
            [
                'name' => 'Shifon Keju',
                'category_id' => $catCake->id,
                'price' => 30000,
                'stock' => 10,
                'description' => 'Kue shifon super ringan dan mengembang empuk dengan taburan keju gurih melimpah.',
                'is_featured' => false,
                'is_new' => false,
            ],
            [
                'name' => 'Cake Redvelvet',
                'category_id' => $catCake->id,
                'price' => 19000,
                'stock' => 15,
                'description' => 'Mini cake red velvet premium dengan rasa elegan yang memanjakan momen bersantaimu.',
                'is_featured' => false,
                'is_new' => false,
            ],
            [
                'name' => 'Tart Mini Bento',
                'category_id' => $catCake->id,
                'price' => 40000,
                'stock' => 10,
                'description' => 'Kue tart cantik kekinian dalam wadah bento yang bisa kamu pesan untuk kejutan spesial.',
                'is_featured' => true,
                'is_new' => true,
            ],
            [
                'name' => 'Tart Diameter 14',
                'category_id' => $catCake->id,
                'price' => 170000,
                'stock' => 5,
                'description' => 'Kue tart ukuran 14cm bernuansa estetik yang tepat untuk merayakan hari kebahagiaanmu.',
                'is_featured' => false,
                'is_new' => false,
            ],

            // Pastry & Donat
            [
                'name' => 'Donat Coklat Cerry',
                'category_id' => $catPastry->id,
                'price' => 8000,
                'stock' => 30,
                'description' => 'Donat montok manis yang diselimuti coklat mengkilap dan sebutir ceri segar di tengahnya.',
                'is_featured' => false,
                'is_new' => false,
            ],
            [
                'name' => 'Donat Oreo',
                'category_id' => $catPastry->id,
                'price' => 8500,
                'stock' => 30,
                'description' => 'Paduan serasi donat lembut dengan baluran topping remahan biskuit Oreo renyah memikat.',
                'is_featured' => false,
                'is_new' => false,
            ],
            [
                'name' => 'Donat Meses Keju',
                'category_id' => $catPastry->id,
                'price' => 8000,
                'stock' => 30,
                'description' => 'Dua rasa andalan dalam satu gigitan: gurihnya keju parut bertemu manisnya meses coklat.',
                'is_featured' => false,
                'is_new' => false,
            ],
            [
                'name' => 'Donat Tiramisu',
                'category_id' => $catPastry->id,
                'price' => 8000,
                'stock' => 25,
                'description' => 'Donat modern berlapis glaze rasa tiramisu pekat untuk teman minum kopimu.',
                'is_featured' => true,
                'is_new' => false,
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
                    'image' => 'product_' . $slug . '.jpg',
                    'is_available' => true,
                    'is_active' => true,
                    'is_featured' => $item['is_featured'],
                    'is_new' => $item['is_new'],
                ]
            );
        }
    }
}
