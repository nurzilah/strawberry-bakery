@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-6 md:py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Title with Back Button --}}
        <div class="mb-6 md:mb-8 flex items-center gap-4">
            <a href="{{ route('profile.show') }}"
               class="text-gray-600 hover:text-gray-800 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Edit Profil</h1>
                <p class="text-gray-500 mt-1 text-sm md:text-base">Perbarui informasi akun pribadi Anda</p>
            </div>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- KIRI: INFO USER --}}
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                {{-- Avatar --}}
                <div class="w-24 h-24 md:w-28 md:h-28 mx-auto rounded-full bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center text-white text-3xl md:text-4xl font-bold shadow-lg">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                {{-- Name --}}
                <h3 class="mt-4 text-lg md:text-xl font-semibold text-gray-800">
                    {{ auth()->user()->name }}
                </h3>

                {{-- Email --}}
                <p class="text-gray-500 text-sm md:text-base mt-1 break-words">
                    {{ auth()->user()->email }}
                </p>

                {{-- Divider --}}
                <div class="my-4 border-t border-gray-200"></div>

                {{-- Join Date --}}
                <div class="text-sm text-gray-500">
                    <p class="mb-1">Bergabung sejak</p>
                    <p class="text-base font-semibold text-gray-700">
                        {{ auth()->user()->created_at->format('d M Y') }}
                    </p>
                </div>
            </div>

            {{-- KANAN: FORM --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6 md:p-8">
                <h2 class="text-xl md:text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Informasi Profil
                </h2>

                <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-5">

                        {{-- Nama --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name"
                                value="{{ old('name', auth()->user()->name) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition"
                                required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <div class="relative">
                                <input type="email" disabled
                                    value="{{ auth()->user()->email }}"
                                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 cursor-not-allowed text-gray-500">
                                <div class="absolute right-3 top-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                No. Handphone
                            </label>
                            <input type="text" name="phone"
                                value="{{ old('phone', auth()->user()->phone) }}"
                                placeholder="Contoh: 08123456789"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Address --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Lengkap
                            </label>
                            <textarea name="address" rows="4"
                                placeholder="Masukkan alamat lengkap Anda..."
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition resize-none">{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="w-full sm:w-auto bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-black px-6 py-2.5 rounded-lg transition font-medium shadow-md hover:shadow-lg">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- Custom Modal Confirmation --}}
<div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full animate-modalShow">
        <!-- Icon -->
        <div class="pt-8 pb-4 flex justify-center">
            <div class="w-20 h-20 bg-gradient-to-br from-pink-400 to-pink-600 rounded-full flex items-center justify-center shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- Content -->
        <div class="px-8 pb-6 text-center">
            <h3 class="text-2xl font-bold text-gray-800 mb-3">
                Konfirmasi Perubahan
            </h3>
            <p class="text-gray-600 text-base mb-8">
                Anda yakin ingin mengubah data ini?
            </p>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="button" id="cancelBtn"
                    class="flex-1 px-5 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg text-gray-700 font-semibold transition-all duration-200">
                    Batal
                </button>
                <button type="button" id="confirmBtn"
                    class="flex-1 px-5 py-3 bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-black rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                    Ya, Ubah
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes modalShow {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.animate-modalShow {
    animation: modalShow 0.3s ease-out;
}
</style>

{{-- Confirmation Script --}}
<script>
const form = document.getElementById('profileForm');
const modal = document.getElementById('confirmModal');
const cancelBtn = document.getElementById('cancelBtn');
const confirmBtn = document.getElementById('confirmBtn');

form.addEventListener('submit', function(e) {
    e.preventDefault();
    modal.classList.remove('hidden');
});

cancelBtn.addEventListener('click', function() {
    modal.classList.add('hidden');
});

confirmBtn.addEventListener('click', function() {
    form.submit();
});

// Close modal when clicking outside
modal.addEventListener('click', function(e) {
    if (e.target === modal) {
        modal.classList.add('hidden');
    }
});
</script>
@endsection
