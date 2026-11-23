<div class="h-screen w-64 bg-slate-900 text-white flex flex-col fixed left-0 top-0 overflow-y-auto shadow-2xl z-50">
    <div class="p-6 border-b border-slate-800 flex items-center justify-center bg-slate-950">
        <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-rose-500">
            SB Admin
        </h2>
    </div>

    <div class="flex-1 py-6 px-4 space-y-2">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4 px-2">Menu Navigasi</p>
        
        {{-- Owner Sees Everything --}}
        @if(in_array(auth()->user()->role, ['owner', 'superadmin']))
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-pink-500/20 text-pink-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-chart-pie w-5 text-center"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.products.*') ? 'bg-pink-500/20 text-pink-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-box w-5 text-center"></i>
                <span class="font-medium">Kelola Produk</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.categories.*') ? 'bg-pink-500/20 text-pink-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-tags w-5 text-center"></i>
                <span class="font-medium">Kelola Kategori</span>
            </a>
            <a href="{{ route('admin.stock.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.stock.*') ? 'bg-pink-500/20 text-pink-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-boxes-stacking w-5 text-center"></i>
                <span class="font-medium">Manajemen Stok</span>
            </a>
            <a href="{{ route('kasir.pos') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('kasir.pos') ? 'bg-pink-500/20 text-pink-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-cash-register w-5 text-center"></i>
                <span class="font-medium">Mesin POS Kasir</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.orders.*') ? 'bg-pink-500/20 text-pink-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-history w-5 text-center"></i>
                <span class="font-medium">Riwayat Transaksi</span>
            </a>
            <a href="{{ route('owner.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('owner.users.*') ? 'bg-pink-500/20 text-pink-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-users w-5 text-center"></i>
                <span class="font-medium">Manajemen Akun</span>
            </a>

        {{-- Admin Sees Dashboard, Produk, Kategori --}}
        @elseif(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-pink-500/20 text-pink-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-chart-pie w-5 text-center"></i>
                <span class="font-medium">Dashboard (Read)</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.products.*') ? 'bg-pink-500/20 text-pink-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-box w-5 text-center"></i>
                <span class="font-medium">Kelola Produk</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.categories.*') ? 'bg-pink-500/20 text-pink-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-tags w-5 text-center"></i>
                <span class="font-medium">Kelola Kategori</span>
            </a>
            <a href="{{ route('admin.stock.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.stock.*') ? 'bg-pink-500/20 text-pink-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-boxes-stacking w-5 text-center"></i>
                <span class="font-medium">Manajemen Stok</span>
            </a>

        {{-- Kasir ONLY Sees POS & Riwayat --}}
        @elseif(auth()->user()->role === 'kasir')
            <a href="{{ route('kasir.pos') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('kasir.pos') ? 'bg-pink-500/20 text-pink-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-cash-register w-5 text-center"></i>
                <span class="font-medium">Mesin POS Kasir</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.orders.*') ? 'bg-pink-500/20 text-pink-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-history w-5 text-center"></i>
                <span class="font-medium">Riwayat Transaksi</span>
            </a>
        @endif
    </div>

    <div class="p-4 border-t border-slate-800 bg-slate-950/50">
        <div class="flex items-center gap-3 px-2 mb-4">
            @if(auth()->user()->avatar)
                <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover shadow-lg shadow-pink-500/30" referrerpolicy="no-referrer">
            @else
                <div class="w-10 h-10 rounded-full bg-pink-500 flex items-center justify-center text-white font-bold shadow-lg shadow-pink-500/30">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
            @endif
            <div>
                <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                <p class="text-xs text-pink-400 uppercase tracking-wider">{{ auth()->user()->role === 'admin' ? 'IT DEVELOPER' : strtoupper(auth()->user()->role) }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-slate-800 text-slate-300 hover:bg-red-500/20 hover:text-red-400 transition-colors duration-300">
                <i class="fas fa-sign-out-alt"></i>
                <span class="font-medium text-sm">Logout</span>
            </button>
        </form>
    </div>
</div>
