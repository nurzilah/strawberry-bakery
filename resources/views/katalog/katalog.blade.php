@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')
<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-8">
        <!-- Sidebar Filter -->
        <aside class="w-full md:w-1/4">
            <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-24">
                <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-4">Filter Produk</h2>
                
                <form action="{{ route('products.index') }}" method="GET">
                    <!-- Kategori -->
                    <div class="mb-6 border-b pb-6">
                        <h3 class="font-semibold text-gray-700 mb-4">Kategori</h3>
                        <div class="space-y-3">
                            @foreach($categories as $category)
                            <label class="flex items-center group cursor-pointer">
                                <input type="radio" name="category" value="{{ $category['slug'] }}" 
                                    class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500"
                                    {{ request('category', 'all') == $category['slug'] ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                <span class="ml-3 text-gray-600 group-hover:text-pink-500 transition">{{ $category['name'] }}</span>
                                <span class="ml-auto text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">{{ $category['count'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Harga -->
                    <div class="mb-6 border-b pb-6">
                        <h3 class="font-semibold text-gray-700 mb-4">Rentang Harga</h3>
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <label class="text-xs text-gray-500 mb-1 block">Min (Rp)</label>
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent transition text-sm">
                            </div>
                            <span class="text-gray-400 mt-5">-</span>
                            <div class="flex-1">
                                <label class="text-xs text-gray-500 mb-1 block">Max (Rp)</label>
                                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="100000" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent transition text-sm">
                            </div>
                        </div>
                        <button type="submit" class="w-full mt-4 bg-gray-100 text-gray-700 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition">Terapkan Harga</button>
                    </div>

                    <!-- Rating -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-700 mb-4">Rating</h3>
                        <div class="space-y-3">
                            @foreach($ratings as $rating)
                            <label class="flex items-center group cursor-pointer">
                                <input type="radio" name="rating" value="{{ $rating['stars'] }}" 
                                    class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500"
                                    {{ request('rating') == $rating['stars'] ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                <div class="flex items-center ml-3">
                                    <div class="flex text-yellow-400">
                                        @for($i = 0; $i < 5; $i++)
                                            <svg class="w-4 h-4 {{ $i < $rating['stars'] ? 'fill-current' : 'fill-gray-300' }}" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-sm text-gray-500">ke atas</span>
                                </div>
                                <span class="ml-auto text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">{{ $rating['count'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    
                    @if(request()->hasAny(['category', 'min_price', 'max_price', 'rating']))
                        <a href="{{ route('products.index') }}" class="block w-full text-center mt-4 text-red-500 hover:text-red-600 text-sm font-medium">Reset Semua Filter</a>
                    @endif
                </form>
            </div>
        </aside>

        <!-- Product Grid -->
        <main class="w-full md:w-3/4">
            <!-- Header Bar -->
            <div class="bg-white rounded-2xl shadow-sm p-4 flex flex-col sm:flex-row items-center justify-between mb-8">
                <p class="text-gray-600 mb-4 sm:mb-0">
                    Menampilkan <span class="font-bold text-gray-800">{{ $paginatedProducts->count() }}</span> dari <span class="font-bold text-gray-800">{{ $paginatedProducts->total() }}</span> produk
                </p>
                
                <form action="{{ route('products.index') }}" method="GET" class="flex items-center space-x-4">
                    <!-- Preserve existing filters -->
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                    @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
                    @if(request('rating')) <input type="hidden" name="rating" value="{{ request('rating') }}"> @endif

                    <label for="sort" class="text-sm text-gray-500">Urutkan:</label>
                    <select id="sort" name="sort" onchange="this.form.submit()" class="border-gray-300 rounded-lg text-sm focus:ring-pink-500 focus:border-pink-500 px-3 py-2 bg-gray-50">
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                    </select>
                </form>
            </div>

            <!-- Products -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($paginatedProducts as $product)
                <div class="card bg-white group">
                    <div class="relative overflow-hidden">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=600' }}" alt="{{ $product->name }}" class="w-full h-56 object-cover group-hover:scale-110 transition duration-500">
                        </a>
                        @if($product->is_promo)
                            <span class="badge badge-promo absolute top-4 left-4">Promo</span>
                        @elseif($product->is_featured)
                            <span class="badge badge-featured absolute top-4 left-4">Unggulan</span>
                        @elseif($product->is_new)
                            <span class="badge badge-new absolute top-4 left-4">Baru</span>
                        @endif
                        <button class="absolute bottom-4 right-4 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition hover:bg-pink-500 hover:text-white" title="Tambahkan ke Keranjang">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="p-5">
                        <span class="text-xs text-pink-500 font-semibold uppercase tracking-wider">{{ $product->category->name ?? 'Kategori' }}</span>
                        <h3 class="text-lg font-bold text-gray-800 mt-1 mb-2 hover:text-pink-500 transition">
                            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                        </h3>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="flex text-yellow-400">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-4 h-4 {{ $i < floor($product->rating) ? 'fill-current' : 'fill-gray-300' }}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-xs text-gray-500">({{ $product->reviews_count }})</span>
                        </div>
                        <div class="flex items-center justify-between mt-auto">
                            <span class="text-xl font-bold text-pink-500">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center bg-white rounded-2xl shadow-sm">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-500">Maaf, kami tidak dapat menemukan produk yang sesuai dengan filter Anda.</p>
                    <a href="{{ route('products.index') }}" class="inline-block mt-4 text-pink-500 hover:text-pink-600 font-medium">Reset Semua Filter</a>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($paginatedProducts->lastPage() > 1)
            <div class="flex justify-center items-center mt-12">
                {{ $paginatedProducts->links() }}
            </div>
            @endif
        </main>
    </div>
</div>
@endsection
