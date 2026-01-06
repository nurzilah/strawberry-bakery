<x-app-layout>
    {{-- Header hanya judul --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Profil Saya
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">

                {{-- Avatar --}}
                <div class="flex items-center space-x-6 mb-6">
                    <div class="w-20 h-20 rounded-full bg-pink-100 flex items-center justify-center
                        text-pink-600 text-3xl font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold">
                            {{ auth()->user()->name }}
                        </h3>
                        <p class="text-gray-500">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>

                {{-- Data --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">No. HP</p>
                        <p>{{ auth()->user()->phone ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Alamat</p>
                        <p>{{ auth()->user()->address ?? '-' }}</p>
                    </div>
                </div>

                {{-- Button di bawah --}}
                <div class="mt-8 flex justify-end">
                    <a href="{{ route('profile.edit') }}"
                       class="bg-pink-500 hover:bg-pink-600 text-white px-5 py-2 rounded-lg">
                        Edit Profil
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
