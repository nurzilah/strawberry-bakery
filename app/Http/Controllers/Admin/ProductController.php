<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Services\ProductImageAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ProductController extends Controller
{
    public function __construct(protected ProductImageAiService $productImageAi)
    {
    }

    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        $recentReviews = \App\Models\ProductReview::with(['user', 'product'])->latest()->paginate(10, ['*'], 'reviews_page');
        return view('admin.products.index', compact('products', 'recentReviews'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.form', [
            'product' => new Product(),
            'categories' => $categories,
            'title' => 'Tambah Produk',
            'method' => 'POST',
            'route'  => route('admin.products.store'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request, null, false);
        try {
            $data = $this->handleUpload($request, $data);
        } catch (RuntimeException $e) {
            return back()->withInput()->withErrors(['image' => $e->getMessage()]);
        }
        $data['slug'] = $this->buildSlug($data, null);

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.form', [
            'product' => $product,
            'categories' => $categories,
            'title' => 'Edit Produk',
            'method' => 'PUT',
            'route'  => route('admin.products.update', $product),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request, $product->id, true);
        try {
            $data = $this->handleUpload($request, $data, $product->image);
        } catch (RuntimeException $e) {
            return back()->withInput()->withErrors(['image' => $e->getMessage()]);
        }
        $data['slug'] = $this->buildSlug($data, $product->id, $product->slug);

        // Check if stock was modified in the edit form
        $oldStock = $product->stock;
        $newStock = (int) $data['stock'];
        
        if ($oldStock !== $newStock) {
            $difference = $newStock - $oldStock;
            $product->addStock($difference, 'Penyesuaian stok dari halaman Edit Produk', auth()->id());
        }
        
        // Unset stock from data so it doesn't double-save or overwrite what addStock just did
        unset($data['stock']);

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    protected function validateData(Request $request, ?int $productId = null, bool $isUpdate = false)
    {
        $imageRule = $isUpdate
            ? 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif|max:5120'
            : 'required|file|mimes:jpg,jpeg,png,webp,heic,heif|max:5120';

        return $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:products,slug,' . ($productId ?? 'NULL') . ',id',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image'       => $imageRule,
            'is_featured' => 'nullable|boolean',
            'is_new'      => 'nullable|boolean',
            'is_promo'    => 'nullable|boolean',
            'is_available'=> 'nullable|boolean',
            'ai_enhance'  => 'nullable|boolean',
            'ai_prompt'   => 'nullable|string|max:1000',
        ], [
            'image.required' => 'Foto produk wajib diunggah.',
            'image.mimes' => 'Format foto harus jpg, jpeg, png, webp, heic, atau heif.',
            'image.max' => 'Ukuran foto maksimal 5MB.',
        ]);
    }

    protected function handleUpload(Request $request, array $data, ?string $oldImage = null): array
    {
        if ($request->hasFile('image')) {
            if ($request->boolean('ai_enhance')) {
                $imageBytes = $this->productImageAi->enhance(
                    $request->file('image'),
                    $data,
                    $request->input('ai_prompt')
                );
                $data['image'] = 'products/ai-' . Str::uuid() . '.png';
                Storage::disk('public')->put($data['image'], $imageBytes);
            } else {
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        } else {
            unset($data['image']);
        }

        unset($data['ai_enhance'], $data['ai_prompt']);

        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);
        $data['is_new'] = (bool) ($data['is_new'] ?? false);
        $data['is_promo'] = (bool) ($data['is_promo'] ?? false);
        $data['is_available'] = (bool) ($data['is_available'] ?? false);

        return $data;
    }

    protected function buildSlug(array $data, ?int $productId = null, ?string $fallback = null): ?string
    {
        $seed = $data['slug'] ?? $data['name'] ?? null;

        if (!$seed) {
            return $fallback;
        }

        $base = Str::slug($seed);
        if ($base === '') {
            return $fallback;
        }

        $slug = $base;
        $counter = 2;
        while (Product::where('slug', $slug)
            ->when($productId, fn ($q) => $q->where('id', '!=', $productId))
            ->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
