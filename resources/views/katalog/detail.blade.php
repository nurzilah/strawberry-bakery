@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="bg-pink-50 py-10">
    <div class="container mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('products.index') }}" class="text-sm text-pink-500 hover:underline">← Kembali ke katalog</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 bg-white rounded-2xl shadow p-6">
            <div class="flex justify-center items-start">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=600' }}"
                     alt="{{ $product->name }}"
                     class="w-full rounded-2xl"
                     style="max-height: 400px; object-fit: cover;">
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-2">{{ $product->category->name ?? 'Kategori' }}</p>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
                
                <!-- Average Rating -->
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($product->rating))
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @else
                                <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endif
                        @endfor
                    </div>
                    <span class="ml-2 text-sm text-gray-600">({{ number_format($product->rating, 1) }}/5 dari {{ $product->reviews_count }} ulasan)</span>
                </div>

                <p class="text-2xl font-bold text-pink-500 mb-4">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
                <p class="text-gray-700 leading-relaxed mb-6">
                    {{ $product->description ?: 'Produk ini dibuat dengan bahan premium, segar, dan cocok untuk menemani momen spesial Anda.' }}
                </p>

                <div class="flex items-center space-x-3 mb-6 text-sm text-gray-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    <span>
                        Pickup: {{ $product->pickup_date ? \Carbon\Carbon::parse($product->pickup_date)->format('d F Y') : 'Minimal H-2' }}
                    </span>
                </div>

                @auth
                <form action="{{ route('cart.store') }}" method="POST" class="flex items-center space-x-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-pink-400 to-red-400 text-white rounded-lg font-semibold hover:shadow-lg transition">
                        Tambah ke Keranjang
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-pink-400 to-red-400 text-white rounded-lg font-semibold hover:shadow-lg transition">
                    Login untuk beli
                </a>
                @endauth
            </div>
        </div>
        </div>

        <!-- Ulasan Pelanggan -->
        <div class="mt-12 bg-white rounded-2xl shadow p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Ulasan Pelanggan</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- List Review -->
                <div>
                    @if($product->reviews->isEmpty())
                        <p class="text-gray-500 italic">Belum ada ulasan untuk produk ini.</p>
                    @else
                        <div class="space-y-6 max-h-96 overflow-y-auto pr-2">
                            @foreach($product->reviews as $review)
                            <div class="border-b pb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-semibold text-gray-800">{{ $review->user->name }}</span>
                                    <span class="text-sm text-gray-500">{{ $review->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="flex text-yellow-400 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-gray-700 text-sm">{{ $review->comment }}</p>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Form Ulasan -->
                <div>
                    @guest
                        <div class="bg-pink-50 rounded-xl p-6 text-center">
                            <p class="text-gray-600 mb-4">Login untuk memberikan ulasan.</p>
                            <a href="{{ route('login') }}" class="inline-block px-6 py-2 bg-pink-500 text-white rounded-lg font-semibold hover:bg-pink-600 transition">Login</a>
                        </div>
                    @endguest

                    @auth
                        @if($userReview)
                            <div class="bg-gray-50 rounded-xl p-6">
                                <h3 class="font-semibold text-gray-800 mb-2">Ulasan Anda</h3>
                                <div class="flex text-yellow-400 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $userReview->rating)
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-gray-700 text-sm mb-4">{{ $userReview->comment }}</p>
                                
                                <form action="{{ route('reviews.destroy', $userReview->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 text-sm hover:underline" onclick="return confirm('Hapus ulasan ini?')">Hapus Ulasan</button>
                                </form>
                            </div>
                        @elseif(!$hasOrdered)
                            <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-100">
                                <p class="text-gray-600 text-sm">Anda hanya dapat memberikan ulasan setelah membeli produk ini dan pesanan berstatus selesai.</p>
                            </div>
                        @else
                            <form action="{{ route('reviews.store', $product->id) }}" method="POST" class="bg-pink-50 rounded-xl p-6">
                                @csrf
                                <h3 class="font-semibold text-gray-800 mb-4">Berikan Ulasan Anda</h3>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                                    <div class="flex text-gray-300 cursor-pointer" id="star-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-8 h-8 star-item fill-current hover:text-yellow-400" data-value="{{ $i }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="rating-input" required>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Komentar (Opsional)</label>
                                    <textarea name="comment" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" placeholder="Bagikan pengalaman Anda..."></textarea>
                                </div>

                                <button type="submit" class="w-full px-4 py-2 bg-pink-500 text-white rounded-lg font-semibold hover:bg-pink-600 transition disabled:opacity-50" id="submit-review" disabled>Kirim Ulasan</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star-item');
        const ratingInput = document.getElementById('rating-input');
        const submitBtn = document.getElementById('submit-review');

        if(stars.length > 0) {
            stars.forEach(star => {
                star.addEventListener('mouseover', function() {
                    const value = this.dataset.value;
                    highlightStars(value);
                });

                star.addEventListener('mouseout', function() {
                    const currentRating = ratingInput.value;
                    highlightStars(currentRating);
                });

                star.addEventListener('click', function() {
                    const value = this.dataset.value;
                    ratingInput.value = value;
                    highlightStars(value);
                    if(submitBtn) submitBtn.disabled = false;
                });
            });
        }

        function highlightStars(value) {
            stars.forEach(star => {
                if (star.dataset.value <= value) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }
    });
</script>
@endpush
