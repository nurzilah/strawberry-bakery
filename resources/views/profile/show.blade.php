@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-br from-pink-50 via-white to-purple-50 min-h-screen py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header tanpa Button Edit --}}
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Profil Saya</h1>
            <p class="text-gray-600 text-sm md:text-base">Informasi akun pribadi Anda</p>
        </div>

        {{-- Success Alert --}}
        @if (session('success'))
            <div class="mb-8 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md animate-fade-in">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left Card: User Info --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl p-8 text-center transform transition-all duration-300 hover:shadow-2xl border border-gray-100">
                    {{-- Avatar --}}
                    <div class="relative inline-block mb-6">
                        <div class="w-32 h-32 mx-auto rounded-full bg-gradient-to-br from-pink-400 via-pink-500 to-pink-600 flex items-center justify-center text-white text-5xl font-bold shadow-2xl ring-4 ring-pink-100 ring-offset-4">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="absolute bottom-0 right-0 w-10 h-10 bg-green-500 rounded-full border-4 border-white shadow-lg"></div>
                    </div>

                    {{-- Name --}}
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        {{ auth()->user()->name }}
                    </h2>

                    {{-- Email --}}
                    <div class="flex items-center justify-center gap-2 text-gray-600 mb-6">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm break-all">{{ auth()->user()->email }}</p>
                    </div>

                    {{-- Divider --}}
                    <div class="w-16 h-1 bg-gradient-to-r from-pink-400 to-purple-400 mx-auto mb-6 rounded-full"></div>

                    {{-- Join Date --}}
                    <div class="bg-gradient-to-br from-pink-50 to-purple-50 rounded-xl p-4">
                        <p class="text-sm text-gray-600 mb-1 font-medium">Bergabung sejak</p>
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-lg font-bold text-gray-800">
                                {{ auth()->user()->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Card: Profile Details --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl p-8 transform transition-all duration-300 hover:shadow-2xl border border-gray-100">
                    {{-- Section Title --}}
                    <div class="flex items-center gap-3 mb-8 pb-4 border-b-2 border-gray-100">
                        <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-pink-600 rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Informasi Profil</h2>
                    </div>

                    {{-- Profile Info Grid --}}
                    <div class="space-y-6">

                        {{-- Nama Lengkap --}}
                        <div class="group">
                            <div class="flex items-start gap-4 p-5 rounded-xl bg-gradient-to-r from-gray-50 to-transparent hover:from-pink-50 hover:to-transparent transition-all duration-300 border border-transparent hover:border-pink-200">
                                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-pink-100 to-pink-200 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <label class="block text-sm font-semibold text-gray-500 mb-1">Nama Lengkap</label>
                                    <p class="text-lg font-bold text-gray-800 break-words">{{ auth()->user()->name }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="group">
                            <div class="flex items-start gap-4 p-5 rounded-xl bg-gradient-to-r from-gray-50 to-transparent hover:from-purple-50 hover:to-transparent transition-all duration-300 border border-transparent hover:border-purple-200">
                                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <label class="block text-sm font-semibold text-gray-500 mb-1">Email</label>
                                    <p class="text-lg font-bold text-gray-800 break-all">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- No. Handphone --}}
                        <div class="group">
                            <div class="flex items-start gap-4 p-5 rounded-xl bg-gradient-to-r from-gray-50 to-transparent hover:from-blue-50 hover:to-transparent transition-all duration-300 border border-transparent hover:border-blue-200">
                                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <label class="block text-sm font-semibold text-gray-500 mb-1">No. Handphone</label>
                                    <p class="text-lg font-bold text-gray-800">
                                        {{ auth()->user()->phone ?? '-' }}
                                    </p>
                                    @if(!auth()->user()->phone)
                                        <p class="text-xs text-gray-400 mt-1 italic">Belum diisi</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Alamat Lengkap --}}
                        <div class="group">
                            <div class="flex items-start gap-4 p-5 rounded-xl bg-gradient-to-r from-gray-50 to-transparent hover:from-green-50 hover:to-transparent transition-all duration-300 border border-transparent hover:border-green-200">
                                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <label class="block text-sm font-semibold text-gray-500 mb-1">Alamat Lengkap</label>
                                    <p class="text-lg font-bold text-gray-800 whitespace-pre-line leading-relaxed">
                                        {{ auth()->user()->address ?? '-' }}
                                    </p>
                                    @if(!auth()->user()->address)
                                        <p class="text-xs text-gray-400 mt-1 italic">Belum diisi</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Action Button di bawah --}}
                    <div class="mt-8 pt-6 border-t-2 border-gray-100">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('profile.edit') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-black px-8 py-4 rounded-xl transition-all duration-300 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <span class="text-base">Edit Profil</span>
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                                @csrf
                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-black px-8 py-4 rounded-xl transition-all duration-300 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    <span class="text-base">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.5s ease-out;
}
</style>
@endsection
