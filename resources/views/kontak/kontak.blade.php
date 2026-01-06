@extends('layouts.app')

@section('content')
{{-- Hero Section --}}
<div class="bg-gradient-to-r from-pink-400 to-pink-500 py-16 md:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">Hubungi Kami</h1>
        <p class="text-white text-sm md:text-base max-w-2xl mx-auto">
            Kami siap membantu Anda! Jangan ragu untuk menghubungi kami
        </p>
    </div>
</div>

{{-- Main Content --}}
<div class="bg-pink-50 py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- Left Side: Contact Info --}}
            <div class="space-y-6">

                {{-- Alamat Toko --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-pink-200 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Alamat Toko</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Jl. AR. Hakim No.149, Mangkukusuman,<br>
                                Kec. Tegal Tim., Kota Tegal, Jawa Tengah
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Telepon --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-pink-200 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Telepon</h3>
                            <a href="tel:+6281575123118" class="text-pink-600 hover:text-pink-700 font-medium block">
                                +62 815-7512-3118
                            </a>
                            <a href="tel:+628584281077" class="text-pink-600 hover:text-pink-700 font-medium block">
                                +62 858-4281-0778
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Email --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-pink-200 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Email</h3>
                            <a href="mailto:info@strawberrybakery.com" class="text-pink-600 hover:text-pink-700 font-medium block">
                                info@strawberrybakery.com
                            </a>
                            <a href="mailto:order@strawberrybakery.com" class="text-pink-600 hover:text-pink-700 font-medium block">
                                order@strawberrybakery.com
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Jam Operasional --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-pink-200 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Jam Operasional</h3>
                            <p class="text-gray-600 text-sm">
                                <span class="font-medium">Senin - Minggu:</span> 08:00 - 21:00
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Side: Contact Form --}}
            <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Kirim Pesan</h2>

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.submit') }}">
                    @csrf

                    <div class="space-y-5">

                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name"
                                value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email"
                                value="{{ old('email') }}"
                                placeholder="nama@email.com"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('email') border-red-500 @enderror"
                                required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nomor Telepon --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="phone"
                                value="{{ old('phone') }}"
                                placeholder="08xxxxxxxxxx"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('phone') border-red-500 @enderror"
                                required>
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Subjek --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Subjek <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="subject"
                                value="{{ old('subject') }}"
                                placeholder="Pilih subjek"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('subject') border-red-500 @enderror"
                                required>
                            @error('subject')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pesan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pesan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="message" rows="5"
                                placeholder="Tulis pesan Anda di sini..."
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition resize-none @error('message') border-red-500 @enderror"
                                required>{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Submit Button --}}
                    <div class="mt-6">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white px-6 py-3 rounded-lg transition font-medium shadow-md hover:shadow-lg">
                            <span class="inline-flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Kirim Pesan
                            </span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- Google Maps --}}
<div class="bg-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Lokasi Kami</h2>
            <p class="text-gray-600">Jl. AR. Hakim No.149, Mangkukusuman, Kec. Tegal Tim., Kota Tegal, Jawa Tengah</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.5678!2d109.1234!3d-6.8765!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwNTInMzUuNCJTIDEwOcKwMDcnMjQuMiJF!5e0!3m2!1sen!2sid!4v1234567890"
                width="100%"
                height="450"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                class="w-full">
            </iframe>
        </div>
        <p class="text-center text-sm text-gray-500 mt-4">
            (Integrate Google Maps atau Ganti dengan link Google Maps Anda)
        </p>
    </div>
</div>

{{-- FAQ Section --}}
<div class="bg-white py-12 md:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <p class="text-pink-600 font-semibold uppercase tracking-wide text-sm mb-2">FAQ</p>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Pertanyaan yang Sering Diajukan</h2>
        </div>

        <div class="space-y-4">

            {{-- FAQ 1 --}}
            <details class="bg-gray-50 rounded-lg p-5 group">
                <summary class="flex items-center justify-between cursor-pointer list-none">
                    <span class="font-semibold text-gray-800">Bagaimana cara melakukan pre-order?</span>
                    <svg class="w-5 h-5 text-gray-600 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <p class="mt-3 text-gray-600 text-sm leading-relaxed">
                    Anda dapat melakukan pre-order melalui website kami dengan memilih produk yang diinginkan,
                    atau menghubungi kami langsung via WhatsApp atau telepon minimal H-2 sebelum tanggal pengambilan.
                </p>
            </details>

            {{-- FAQ 2 --}}
            <details class="bg-gray-50 rounded-lg p-5 group">
                <summary class="flex items-center justify-between cursor-pointer list-none">
                    <span class="font-semibold text-gray-800">Metode pembayaran apa saja yang tersedia?</span>
                    <svg class="w-5 h-5 text-gray-600 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <p class="mt-3 text-gray-600 text-sm leading-relaxed">
                    Kami menerima pembayaran tunai, transfer bank (BCA, Mandiri, BRI), e-wallet (OVO, GoPay, Dana),
                    dan kartu kredit/debit untuk pembelian online.
                </p>
            </details>

            {{-- FAQ 3 --}}
            <details class="bg-gray-50 rounded-lg p-5 group">
                <summary class="flex items-center justify-between cursor-pointer list-none">
                    <span class="font-semibold text-gray-800">Apakah bisa membatalkan pesanan?</span>
                    <svg class="w-5 h-5 text-gray-600 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <p class="mt-3 text-gray-600 text-sm leading-relaxed">
                    Pembatalan pesanan dapat dilakukan maksimal H-1 sebelum tanggal pengambilan.
                    Untuk pembatalan di hari H, mohon hubungi kami terlebih dahulu.
                </p>
            </details>

            {{-- FAQ 4 --}}
            <details class="bg-gray-50 rounded-lg p-5 group">
                <summary class="flex items-center justify-between cursor-pointer list-none">
                    <span class="font-semibold text-gray-800">Berapa lama roti dapat bertahan?</span>
                    <svg class="w-5 h-5 text-gray-600 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <p class="mt-3 text-gray-600 text-sm leading-relaxed">
                    Roti kami tanpa pengawet dapat bertahan 2-3 hari pada suhu ruang, dan hingga 7 hari jika disimpan di kulkas.
                    Untuk kesegaran maksimal, sebaiknya dikonsumsi dalam 24 jam.
                </p>
            </details>

            {{-- FAQ 5 --}}
            <details class="bg-gray-50 rounded-lg p-5 group">
                <summary class="flex items-center justify-between cursor-pointer list-none">
                    <span class="font-semibold text-gray-800">Apakah tersedia untuk pengiriman?</span>
                    <svg class="w-5 h-5 text-gray-600 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <p class="mt-3 text-gray-600 text-sm leading-relaxed">
                    Ya, kami melayani pengiriman untuk area Kota Tegal dan sekitarnya.
                    Untuk pengiriman ke luar kota, silakan hubungi kami untuk informasi lebih lanjut.
                </p>
            </details>

            {{-- FAQ 6 --}}
            <details class="bg-gray-50 rounded-lg p-5 group">
                <summary class="flex items-center justify-between cursor-pointer list-none">
                    <span class="font-semibold text-gray-800">Bagaimana sistem rekomendasi produk bekerja?</span>
                    <svg class="w-5 h-5 text-gray-600 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <p class="mt-3 text-gray-600 text-sm leading-relaxed">
                    Sistem rekomendasi kami menggunakan algoritma machine learning yang menganalisis riwayat pembelian
                    dan preferensi Anda untuk memberikan saran produk yang sesuai dengan selera Anda.
                </p>
            </details>

        </div>
    </div>
</div>

{{-- Social Media Section --}}
<div class="bg-pink-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">Ikuti Kami</h2>
        <p class="text-gray-600 mb-8">Dapatkan update terbaru dan promo menarik di media sosial kami</p>

        <div class="flex justify-center items-center space-x-4">
            {{-- Facebook --}}
            <a href="#" class="w-12 h-12 bg-white rounded-full flex items-center justify-center hover:bg-pink-500 hover:text-white transition shadow-md">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
            </a>

            {{-- Instagram --}}
            <a href="#" class="w-12 h-12 bg-white rounded-full flex items-center justify-center hover:bg-pink-500 hover:text-white transition shadow-md">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
            </a>

            {{-- WhatsApp --}}
            <a href="https://wa.me/6281575123118" target="_blank" class="w-12 h-12 bg-white rounded-full flex items-center justify-center hover:bg-pink-500 hover:text-white transition shadow-md">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882
