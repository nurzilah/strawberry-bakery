@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="bg-pink-50 py-[4.5rem]">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-pink-200 text-pink-600 rounded-xl text-sm font-medium hover:bg-pink-50 hover:border-pink-300 hover:shadow-sm transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke katalog
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 bg-white rounded-2xl shadow p-6 lg:p-8">
            <div class="flex justify-center items-start">
                <img src="{{ $product->image_default }}"
                     alt="{{ $product->name }}"
                     class="w-full rounded-2xl"
                     style="max-height: 400px; object-fit: cover;"
                     onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'">
            </div>
            <div class="flex flex-col justify-center">
                <p class="text-sm font-medium text-pink-500 uppercase tracking-wider mb-1">{{ $product->category->name ?? 'Kategori' }}</p>
                <h1 class="text-3xl font-bold text-gray-800 mb-3">{{ $product->name }}</h1>
                
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
                    <span class="ml-2 text-sm text-gray-500">({{ number_format($product->rating, 1) }}/5 · {{ $product->reviews_count }} ulasan)</span>
                </div>

                <p class="text-3xl font-bold text-pink-500 mb-4">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
                <p class="text-gray-600 leading-relaxed mb-6">
                    {{ $product->description ?: 'Produk ini dibuat dengan bahan premium, segar, dan cocok untuk menemani momen spesial Anda.' }}
                </p>

                <div class="flex items-center gap-2 mb-6 text-sm text-gray-500">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    <span>
                        Pickup: {{ $product->pickup_date ? \Carbon\Carbon::parse($product->pickup_date)->format('d F Y') : 'Minimal H-2' }}
                    </span>
                </div>

                @auth
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-pink-400 to-red-400 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-pink-200 hover:-translate-y-0.5 transition-all duration-200">
                        + Tambah ke Keranjang
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-pink-400 to-red-400 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-pink-200 hover:-translate-y-0.5 transition-all duration-200">
                    Login untuk beli
                </a>
                @endauth
            </div>
        </div>

        <!-- Ulasan Pelanggan -->
        <div id="ulasan" class="mt-12">
            <div class="bg-white rounded-2xl shadow p-6 lg:p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-8 flex items-center gap-2">
                    <svg class="w-6 h-6 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zm-4 0H9v2h2V9z" clip-rule="evenodd"/>
                    </svg>
                    Ulasan Pelanggan
                </h2>

                @if($product->reviews->isNotEmpty())
                @php
                    $avgRating = $product->rating;
                    $totalReviews = $product->reviews_count;
                    $counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                    foreach ($product->reviews as $r) {
                        $counts[$r->rating] = isset($counts[$r->rating]) ? $counts[$r->rating] + 1 : 1;
                    }
                @endphp
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10 pb-10 border-b border-gray-100">
                    <div class="flex flex-col items-center justify-center bg-pink-50 rounded-xl p-6">
                        <span class="text-5xl font-bold text-gray-800">{{ number_format($avgRating, 1) }}</span>
                        <div class="flex text-yellow-400 my-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($avgRating))
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endif
                            @endfor
                        </div>
                        <span class="text-sm text-gray-500">{{ $totalReviews }} ulasan</span>
                    </div>
                    <div class="lg:col-span-2 space-y-2">
                        @for($star = 5; $star >= 1; $star--)
                        @php
                            $count = $counts[$star] ?? 0;
                            $pct = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                        @endphp
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-medium text-gray-600 w-6 text-right">{{ $star }}</span>
                            <svg class="w-4 h-4 text-yellow-400 fill-current flex-shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <div class="flex-1 h-2.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-yellow-400 rounded-full transition-all duration-300" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-sm text-gray-500 w-10 text-right">{{ $count }}</span>
                        </div>
                        @endfor
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        @if($product->reviews->isEmpty())
                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <p class="text-gray-500 font-medium">Belum ada ulasan untuk produk ini.</p>
                                <p class="text-gray-400 text-sm mt-1">Jadilah yang pertama memberikan ulasan!</p>
                            </div>
                        @else
                            <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2 scrollbar-thin">
                                @foreach($product->reviews as $review)
                                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:border-pink-200 hover:shadow-sm transition-all duration-200">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-red-400 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-2 mb-1">
                                                <span class="font-semibold text-gray-800 text-sm truncate">{{ $review->user->name }}</span>
                                                <span class="text-xs text-gray-400 flex-shrink-0">{{ $review->created_at->format('d M Y') }}</span>
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
                                            <p class="text-gray-600 text-sm leading-relaxed">{{ $review->comment }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div>
                        @guest
                            <div class="bg-pink-50 rounded-xl p-8 text-center border border-pink-100">
                                <svg class="w-12 h-12 text-pink-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <p class="text-gray-600 font-medium mb-1">Ingin memberikan ulasan?</p>
                                <p class="text-gray-500 text-sm mb-4">Silakan login terlebih dahulu.</p>
                                <a href="{{ route('login') }}" class="inline-block px-6 py-2.5 bg-pink-500 text-white rounded-lg font-semibold hover:bg-pink-600 hover:shadow-md transition-all duration-200">Login</a>
                            </div>
                        @endguest

                        @auth
                            @if($userReview)
                                <div class="bg-pink-50 rounded-xl p-6 border border-pink-100">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-red-400 flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-800">Ulasan Anda</h3>
                                            <p class="text-xs text-gray-500">{{ $userReview->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex text-yellow-400 mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $userReview->rating)
                                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @else
                                                <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-gray-700 text-sm mb-4 bg-white rounded-lg p-3 border border-pink-100">{{ $userReview->comment }}</p>
                                    <form action="{{ route('reviews.destroy', $userReview->id) }}" method="POST" class="form-confirm" data-confirm-message="Hapus ulasan ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 text-red-500 text-sm hover:text-red-700 hover:underline transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus Ulasan
                                        </button>
                                    </form>
                                </div>
                            @elseif(!$hasOrdered)
                                <div class="bg-gray-50 rounded-xl p-8 text-center border border-gray-200">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-gray-600 font-medium mb-1">Belum bisa memberi ulasan</p>
                                    <p class="text-gray-400 text-sm">Anda hanya dapat memberikan ulasan setelah membeli produk ini dan pesanan berstatus selesai.</p>
                                </div>
                            @else
                                <form action="{{ route('reviews.store', $product->id) }}" method="POST" class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                                    @csrf
                                    <div class="flex items-center gap-3 mb-5">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-red-400 flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                        <h3 class="font-semibold text-gray-800">Berikan Ulasan Anda</h3>
                                    </div>
                                    
                                    <div class="mb-5">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                                        <div class="flex text-gray-300 gap-1 cursor-pointer" id="star-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-8 h-8 star-item fill-current hover:text-yellow-400 transition-colors" data-value="{{ $i }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="rating" id="rating-input" value="5" required>
                                    </div>

                                    <div class="mb-5">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Komentar <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                        <textarea name="comment" rows="4" class="w-full rounded-lg border-gray-200 shadow-sm focus:border-pink-400 focus:ring focus:ring-pink-100 focus:ring-opacity-50 resize-none transition" placeholder="Bagikan pengalaman Anda dengan produk ini..."></textarea>
                                    </div>

                                    <button type="submit" id="submit-review" class="w-full px-4 py-3 bg-gradient-to-r from-pink-400 to-red-400 text-white rounded-lg font-semibold hover:shadow-lg hover:shadow-pink-200 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Kirim Ulasan
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

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

            highlightStars(ratingInput.value);
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
