<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Kue kering Lebaran',
            'Tart Karakter',
            'Tar 2 layer',
            'Tart bento',
            'Tart Siram (Diameter 14cm)',
            'Roti Paketan',
            'Tart Red velvet',
            'Christmas Edition',
            'Mom Edition',
            'Tart Siram',
            'Box Snack',
        ];

        foreach ($categories as $category) {
            \App\Models\Category::firstOrCreate([
                'slug' => \Illuminate\Support\Str::slug($category)
            ], [
                'name' => $category,
                'description' => 'Kategori ' . $category,
                'icon' => 'cake', // default icon
                'is_active' => true,
            ]);
        }
    }
}
