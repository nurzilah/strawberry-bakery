<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;

class TransactionFlowTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $product;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->category = Category::create([
            'name' => 'Kategori Test',
            'slug' => 'kategori-test',
        ]);

        $this->user = User::create([
            'name' => 'User Test',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Produk Test',
            'slug' => 'produk-test',
            'description' => 'Deskripsi',
            'price' => 15000,
            'stock' => 10,
        ]);
    }

    public function test_user_can_add_to_cart_if_stock_sufficient()
    {
        $response = $this->actingAs($this->user)->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response->assertSessionHas('success');
        
        if (Schema::hasColumn('carts', 'product_id')) {
            $this->assertDatabaseHas('carts', [
                'user_id' => $this->user->id,
                'product_id' => $this->product->id,
                'quantity' => 2
            ]);
        }
    }

    public function test_user_cannot_add_to_cart_if_stock_insufficient()
    {
        $response = $this->actingAs($this->user)->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 15, // Melebihi stok 10
        ]);

        $response->assertSessionHas('error');
    }

    public function test_checkout_process_deducts_stock()
    {
        // 1. Tambah ke keranjang
        $this->actingAs($this->user)->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 3,
        ]);

        // 2. Lakukan checkout
        // Kita abaikan error dari Midtrans karena kita hanya mentes perubahan stok di database
        try {
            $this->actingAs($this->user)->post(route('checkout.process'), [
                'payment_method' => 'midtrans',
            ]);
        } catch (\Exception $e) {
            // Abaikan jika error midtrans api
        }

        // 3. Pastikan stok berkurang
        $this->product->refresh();
        $this->assertEquals(7, $this->product->stock); // 10 - 3 = 7
        
        // 4. Pastikan purchase_count bertambah
        $this->assertEquals(3, $this->product->purchase_count);
    }
}
