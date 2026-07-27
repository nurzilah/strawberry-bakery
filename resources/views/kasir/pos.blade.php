<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Kasir - Strawberry Bakery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }

        .product-card {
            transition: all 0.15s ease;
            user-select: none;
        }
        .product-card:active { transform: scale(0.97); }
        .product-card.out-of-stock { opacity: 0.5; pointer-events: none; }

        .category-tab {
            transition: all 0.15s ease;
            cursor: pointer;
            white-space: nowrap;
        }
        .category-tab.active {
            background: #ec4899;
            color: white;
            box-shadow: 0 2px 8px rgba(236,72,153,0.3);
        }

        .cart-item { transition: all 0.15s ease; }

        .qty-btn {
            width: 28px; height: 28px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 6px;
            transition: all 0.15s ease;
            cursor: pointer;
            font-weight: 700;
            font-size: 14px;
        }
        .qty-btn:hover { background: #fce7f3; color: #ec4899; }

        .quick-cash-btn {
            transition: all 0.15s ease;
            cursor: pointer;
            font-weight: 700;
        }
        .quick-cash-btn:hover { background: #fce7f3; color: #ec4899; transform: translateY(-1px); }
        .quick-cash-btn:active { transform: translateY(0); }

        #btnSubmit:not(:disabled) { cursor: pointer; }

        .payment-method-btn {
            transition: all 0.15s ease;
            cursor: pointer;
        }
        .payment-method-btn.active {
            border-color: #ec4899;
            background: #fdf2f8;
            color: #ec4899;
            font-weight: 700;
        }

        .stock-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(4px);
        }

        @media print { .no-print { display: none !important; } }

        .flash-overlay {
            position: fixed; inset: 0; z-index: 9999;
            pointer-events: none;
            transition: opacity 0.3s;
            opacity: 0;
        }
        .flash-overlay.show { opacity: 1; }
        .flash-overlay.success { background: rgba(22,163,74,0.08); }
        .flash-overlay.error { background: rgba(220,38,38,0.08); }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type="number"] { -moz-appearance: textfield; }

        .kbd {
            display: inline-block;
            padding: 1px 5px;
            font-size: 10px;
            font-weight: 700;
            font-family: monospace;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            color: #64748b;
        }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 antialiased">

    <div class="flash-overlay" id="flashOverlay"></div>

    <div class="sidebar-overlay fixed inset-0 bg-black/50 z-[90] hidden opacity-0 transition-opacity duration-300 lg:hidden" id="sidebarOverlay"></div>

    <nav id="sidebar" class="no-print fixed lg:static top-0 left-0 h-screen bg-white border-r border-slate-200 flex flex-col z-[100] transition-transform duration-300 w-[260px] -translate-x-full lg:translate-x-0 shadow-[4px_0_15px_rgba(0,0,0,0.05)] lg:shadow-none overflow-x-hidden">
        <div class="p-[1.25rem_1.5rem] border-b border-black/5 flex items-center gap-4 min-h-[72px] transition-all duration-300 overflow-hidden whitespace-nowrap">
            <img src="{{ asset('images/logo.png') }}" alt="SB" class="w-10 h-10 object-contain shrink-0">
            <div class="sidebar-text overflow-hidden flex flex-col items-start transition-opacity duration-300">
                <span class="font-bold text-slate-800 text-[1rem] leading-[1.2] truncate w-full">{{ \Illuminate\Support\Str::limit(auth()->user()->name, 15) }}</span>
                <div class="mt-1">
                    @php
                        $role = auth()->user()->role ?? 'guest';
                        $roleColors = ['owner'=>'#d63384','superadmin'=>'#d63384','admin'=>'#0d6efd','kasir'=>'#198754'];
                        $bg = $roleColors[$role] ?? '#6c757d';
                        $label = strtoupper($role);
                    @endphp
                    <span class="text-white text-[0.65rem] px-[0.6em] py-[0.35em] rounded font-bold tracking-[0.5px]" style="background-color: {{ $bg }};">{{ $label }}</span>
                </div>
            </div>
        </div>

        <ul class="flex-1 overflow-y-auto overflow-x-hidden p-[1.25rem_0.85rem] flex flex-col gap-[0.35rem] list-none">
            @if(in_array($role, ['owner','superadmin']))
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center text-[#475569] hover:text-[#e94f70] hover:bg-[#e94f70]/5 rounded-[8px] p-[0.75rem_1rem] text-[0.95rem] font-medium transition-colors whitespace-nowrap">
                    <i class="fas fa-chart-pie w-[24px] text-center text-[#94a3b8] text-[1.15rem] mr-[0.75rem]"></i> <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products.index') }}" class="flex items-center text-[#475569] hover:text-[#e94f70] hover:bg-[#e94f70]/5 rounded-[8px] p-[0.75rem_1rem] text-[0.95rem] font-medium transition-colors whitespace-nowrap">
                    <i class="fas fa-box-open w-[24px] text-center text-[#94a3b8] text-[1.15rem] mr-[0.75rem]"></i> <span class="sidebar-text">Kelola Produk</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center text-[#475569] hover:text-[#e94f70] hover:bg-[#e94f70]/5 rounded-[8px] p-[0.75rem_1rem] text-[0.95rem] font-medium transition-colors whitespace-nowrap">
                    <i class="fas fa-tags w-[24px] text-center text-[#94a3b8] text-[1.15rem] mr-[0.75rem]"></i> <span class="sidebar-text">Kelola Kategori</span>
                </a>
            </li>
            @endif
            
            <li class="relative">
                <a href="{{ route('kasir.pos') }}" class="flex items-center text-[#c93455] bg-[#e94f70]/10 rounded-[8px] p-[0.75rem_1rem] text-[0.95rem] font-[600] transition-colors whitespace-nowrap">
                    <div class="absolute left-[-0.85rem] top-[10%] h-[80%] w-[4px] bg-[#e94f70] rounded-r-[4px]"></div>
                    <i class="fas fa-cash-register w-[24px] text-center text-[#e94f70] text-[1.15rem] mr-[0.75rem]"></i> <span class="sidebar-text">Mesin POS / Kasir</span>
                </a>
            </li>
            
            @if(in_array($role, ['owner','superadmin','admin']))
            <li>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center text-[#475569] hover:text-[#e94f70] hover:bg-[#e94f70]/5 rounded-[8px] p-[0.75rem_1rem] text-[0.95rem] font-medium transition-colors whitespace-nowrap">
                    <i class="fas fa-shopping-cart w-[24px] text-center text-[#94a3b8] text-[1.15rem] mr-[0.75rem]"></i> <span class="sidebar-text">Pesanan Online</span>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('kasir.transactions.index') }}" class="flex items-center text-[#475569] hover:text-[#e94f70] hover:bg-[#e94f70]/5 rounded-[8px] p-[0.75rem_1rem] text-[0.95rem] font-medium transition-colors whitespace-nowrap">
                    <i class="fas fa-history w-[24px] text-center text-[#94a3b8] text-[1.15rem] mr-[0.75rem]"></i> <span class="sidebar-text">Riwayat Transaksi</span>
                </a>
            </li>
            @if(in_array($role, ['owner','superadmin']))
            <li>
                <a href="{{ route('owner.users.index') }}" class="flex items-center text-[#475569] hover:text-[#e94f70] hover:bg-[#e94f70]/5 rounded-[8px] p-[0.75rem_1rem] text-[0.95rem] font-medium transition-colors whitespace-nowrap">
                    <i class="fas fa-users w-[24px] text-center text-[#94a3b8] text-[1.15rem] mr-[0.75rem]"></i> <span class="sidebar-text">Manajemen Akun</span>
                </a>
            </li>
            <li>
                <a href="{{ route('owner.reports.index') }}" class="flex items-center text-[#475569] hover:text-[#e94f70] hover:bg-[#e94f70]/5 rounded-[8px] p-[0.75rem_1rem] text-[0.95rem] font-medium transition-colors whitespace-nowrap">
                    <i class="fas fa-file-invoice-dollar w-[24px] text-center text-[#94a3b8] text-[1.15rem] mr-[0.75rem]"></i> <span class="sidebar-text">Laporan Penjualan</span>
                </a>
            </li>
            @endif
            @if(in_array($role, ['kasir']))
            <li>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center text-[#475569] hover:text-[#e94f70] hover:bg-[#e94f70]/5 rounded-[8px] p-[0.75rem_1rem] text-[0.95rem] font-medium transition-colors whitespace-nowrap">
                    <i class="fas fa-shopping-cart w-[24px] text-center text-[#94a3b8] text-[1.15rem] mr-[0.75rem]"></i> <span class="sidebar-text">Update Pesanan</span>
                </a>
            </li>
            @endif
        </ul>

        <div class="p-[1rem_0.85rem] border-t border-black/5">
            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari kasir?');">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center text-[#dc2626] hover:bg-[#dc2626]/5 rounded-[8px] p-[0.75rem_1rem] text-[0.95rem] font-medium transition-colors whitespace-nowrap border border-transparent hover:border-[#dc2626]/20">
                    <i class="fas fa-sign-out-alt w-[24px] text-center text-[1.15rem] mr-[0.75rem]"></i> <span class="sidebar-text">Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <div class="flex-1 flex flex-row w-full h-full overflow-hidden no-print">

        <div class="w-[68%] h-full flex flex-col bg-white border-r border-slate-200">
            <div class="p-3 border-b border-slate-100 flex items-center justify-between gap-3 bg-white">
                <div class="flex items-center gap-2">
                    <button type="button" id="sidebarCollapse" class="lg:hidden text-slate-400 hover:text-slate-600 transition p-1 mr-1">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <i class="fas fa-cash-register text-pink-500 text-lg"></i>
                    <h1 class="text-base font-bold text-slate-800">Terminal POS</h1>
                </div>
                <div class="relative flex-1 max-w-md">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="searchInput" placeholder="Cari produk... (scan barcode Enter)" class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-400/20 transition-all text-sm">
                </div>
                <div class="flex items-center gap-2 text-[10px] text-slate-400 font-medium">
                    <span><span class="kbd">F1</span> Cari</span>
                    <span><span class="kbd">F2</span> Diskon</span>
                    <span><span class="kbd">F8</span> Bayar</span>
                </div>
            </div>

            <div class="border-b border-slate-100 bg-white">
                <div class="flex overflow-x-auto py-2 px-3 gap-1.5 category-scroll" id="categoryTabs">
                    <button class="category-tab active px-3 py-1.5 rounded-lg text-xs font-bold bg-pink-500 text-white" data-category="all" onclick="filterCategory('all')">
                        <i class="fas fa-th-large mr-1"></i> Semua
                    </button>
                    @foreach($categories as $category)
                    <button class="category-tab px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200" data-category="{{ $category->id }}" onclick="filterCategory('{{ $category->id }}')">
                        {{ $category->name }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-3 bg-slate-50" id="productContainer">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2.5" id="productGrid">
                    @foreach($products as $product)
                    <div class="product-card group relative bg-white rounded-xl shadow-sm hover:shadow-md border border-slate-100 overflow-hidden flex flex-col {{ $product->stock !== null && $product->stock <= 0 ? 'out-of-stock' : '' }}"
                         data-id="{{ $product->id }}"
                         data-name="{{ $product->name }}"
                         data-price="{{ $product->price }}"
                         data-stock="{{ $product->stock }}"
                         data-category="{{ $product->category_id }}"
                         onclick="addToCart({{ $product->id }})">
                        <div class="h-28 w-full bg-slate-100 relative overflow-hidden">
                            <img src="{{ $product->image_default }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $product->name }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'">
                            @if($product->stock !== null)
                            <div class="absolute top-1.5 right-1.5 stock-badge text-slate-700">
                                <i class="fas fa-box {{ $product->stock <= 5 ? 'text-red-500' : 'text-green-500' }} mr-0.5"></i>
                                {{ $product->stock }}
                            </div>
                            @endif
                            @if($product->stock !== null && $product->stock <= 0)
                            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[1px] flex items-center justify-center">
                                <span class="bg-red-500 text-white text-[10px] font-bold px-3 py-0.5 rounded-full shadow border border-white transform -rotate-12">HABIS</span>
                            </div>
                            @endif
                        </div>
                        <div class="p-2.5 flex-1 flex flex-col justify-between">
                            <h3 class="font-bold text-slate-700 text-[11px] leading-tight line-clamp-2 mb-1">{{ $product->name }}</h3>
                            <p class="text-pink-500 font-black text-xs">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div id="noProducts" class="hidden flex flex-col items-center justify-center py-16 text-slate-400">
                    <i class="fas fa-box-open fa-3x mb-3 opacity-30"></i>
                    <p class="font-semibold text-sm">Tidak ada produk</p>
                    <p class="text-xs mt-1">Produk tidak ditemukan untuk kategori ini</p>
                </div>
            </div>

            <div class="border-t border-slate-100 bg-white px-3 py-1.5 flex items-center gap-3 text-[10px] text-slate-400">
                <span><i class="fas fa-mouse-pointer mr-1"></i> Klik produk untuk tambah</span>
                <span><i class="fas fa-sync mr-1"></i> Klik qty untuk ubah jumlah</span>
            </div>
        </div>

        <div class="w-[32%] h-full flex flex-col bg-white shadow-[-4px_0_12px_rgba(0,0,0,0.03)]">
            <div class="px-3 py-2.5 border-b border-slate-100 bg-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-shopping-basket text-pink-500 text-sm"></i>
                    <h2 class="text-sm font-bold text-slate-800">Tagihan</h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="bg-pink-100 text-pink-600 text-[10px] font-bold px-2 py-0.5 rounded-full" id="cartItemCount">0 item</span>
                    <button onclick="clearCart()" class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Kosongkan Keranjang (F5)">
                        <i class="fas fa-trash-alt text-xs"></i>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-3 py-2 space-y-1.5 bg-slate-50/30" id="cartContainer">
                <div id="emptyCart" class="h-full flex flex-col items-center justify-center text-slate-400 py-12">
                    <i class="fas fa-shopping-basket fa-3x mb-3 opacity-20"></i>
                    <p class="font-semibold text-xs">Belum ada pesanan</p>
                    <p class="text-[10px] mt-0.5 text-slate-400">Klik produk untuk menambahkan</p>
                </div>
            </div>

            <div class="px-3 py-2.5 bg-white border-t border-slate-200 space-y-2">
                <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between items-center text-slate-600">
                        <span class="font-medium">Subtotal</span>
                        <span class="font-bold text-slate-800" id="displaySubtotal">Rp 0</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="font-medium text-slate-600 min-w-[60px]">Diskon</span>
                        <div class="relative flex-1">
                            <span class="absolute left-2 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] font-bold">Rp</span>
                            <input type="number" id="discountInput" value="0" min="0" class="w-full pl-7 pr-2 py-1.5 text-right border border-slate-200 rounded-lg focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400/20 text-xs font-bold text-slate-800 bg-slate-50" oninput="calculateTotal()">
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="font-medium text-slate-600 min-w-[60px]">Pajak</span>
                        <select id="taxRateSelect" class="flex-1 py-1.5 px-2 border border-slate-200 rounded-lg focus:outline-none focus:border-pink-400 text-xs font-semibold text-slate-700 bg-slate-50" onchange="calculateTotal()">
                            <option value="0">0% (Non PPN)</option>
                            <option value="11">11% (PPN)</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="font-medium text-slate-600 min-w-[60px]">Biaya</span>
                        <div class="relative flex-1">
                            <span class="absolute left-2 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] font-bold">Rp</span>
                            <input type="number" id="extraChargeInput" value="0" min="0" class="w-full pl-7 pr-2 py-1.5 text-right border border-slate-200 rounded-lg focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400/20 text-xs font-bold text-slate-800 bg-slate-50" oninput="calculateTotal()">
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="font-medium text-slate-600 min-w-[60px]">Label</span>
                        <input type="text" id="extraChargeLabel" placeholder="Mis: Packing" maxlength="100" class="flex-1 py-1.5 px-2 border border-slate-200 rounded-lg focus:outline-none focus:border-pink-400 text-xs text-slate-700 bg-slate-50" oninput="calculateTotal()">
                    </div>

                    <div id="taxDetail" class="hidden flex justify-between text-slate-500">
                        <span>PPN <span id="taxRateDisplay">0</span>%</span>
                        <span id="displayTax">Rp 0</span>
                    </div>

                    <div id="extraChargeDetail" class="hidden flex justify-between text-slate-500">
                        <span id="extraChargeLabelDisplay">Biaya Tambahan</span>
                        <span id="displayExtraCharge">Rp 0</span>
                    </div>

                    <div id="roundingDetail" class="hidden flex justify-between text-slate-500">
                        <span>Pembulatan</span>
                        <span id="displayRounding">Rp 0</span>
                    </div>

                    <div class="flex justify-between items-center py-1.5 border-t-2 border-slate-800">
                        <span class="text-sm font-black text-slate-800">Total Akhir</span>
                        <span class="text-lg font-black text-pink-600" id="displayGrandTotal">Rp 0</span>
                    </div>
                </div>

                <div class="pt-1 border-t border-slate-100 space-y-2">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Metode Pembayaran</label>
                        <div class="grid grid-cols-4 gap-1.5" id="paymentMethodGroup">
                            <button class="payment-method-btn active px-2 py-2 rounded-lg text-[10px] font-bold border-2 border-pink-500 bg-pink-50 text-pink-600" data-method="Tunai" onclick="selectPaymentMethod('Tunai')">
                                <i class="fas fa-money-bill-wave block mx-auto mb-0.5 text-sm"></i> Tunai
                            </button>
                            <button class="payment-method-btn px-2 py-2 rounded-lg text-[10px] font-bold border-2 border-slate-200 bg-white text-slate-600 hover:border-slate-300" data-method="Transfer" onclick="selectPaymentMethod('Transfer')">
                                <i class="fas fa-university block mx-auto mb-0.5 text-sm"></i> Transfer
                            </button>
                            <button class="payment-method-btn px-2 py-2 rounded-lg text-[10px] font-bold border-2 border-slate-200 bg-white text-slate-600 hover:border-slate-300" data-method="QRIS" onclick="selectPaymentMethod('QRIS')">
                                <i class="fas fa-qrcode block mx-auto mb-0.5 text-sm"></i> QRIS
                            </button>
                            <button class="payment-method-btn px-2 py-2 rounded-lg text-[10px] font-bold border-2 border-slate-200 bg-white text-slate-600 hover:border-slate-300" data-method="Kartu" onclick="selectPaymentMethod('Kartu')">
                                <i class="fas fa-credit-card block mx-auto mb-0.5 text-sm"></i> Kartu
                            </button>
                        </div>
                    </div>

                    <div id="cashPaymentSection">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Jumlah Bayar</label>
                        <input type="number" id="amountPaidInput" placeholder="0" class="w-full py-2 px-3 text-lg font-black text-slate-800 border-2 border-slate-300 rounded-xl focus:outline-none focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 text-right bg-slate-50" oninput="calculateChange()" autocomplete="off">
                        <div class="grid grid-cols-6 gap-1 mt-1.5">
                            <button onclick="setQuickCash(5000)" class="quick-cash-btn py-1.5 bg-slate-100 rounded-lg text-[10px]">5rb</button>
                            <button onclick="setQuickCash(10000)" class="quick-cash-btn py-1.5 bg-slate-100 rounded-lg text-[10px]">10rb</button>
                            <button onclick="setQuickCash(20000)" class="quick-cash-btn py-1.5 bg-slate-100 rounded-lg text-[10px]">20rb</button>
                            <button onclick="setQuickCash(50000)" class="quick-cash-btn py-1.5 bg-slate-100 rounded-lg text-[10px]">50rb</button>
                            <button onclick="setQuickCash(100000)" class="quick-cash-btn py-1.5 bg-slate-100 rounded-lg text-[10px]">100rb</button>
                            <button onclick="setExact()" class="quick-cash-btn py-1.5 bg-pink-500 text-white rounded-lg text-[10px] hover:bg-pink-600">Pas</button>
                        </div>
                    </div>

                    <div id="nonCashNotice" class="hidden p-2 bg-blue-50 border border-blue-200 rounded-lg text-[10px] text-blue-700 font-medium">
                        <i class="fas fa-info-circle mr-1"></i>
                        Konfirmasi pembayaran <span id="nonCashMethod">Transfer</span> dari customer sebelum menyelesaikan transaksi.
                    </div>

                    <div class="flex justify-between items-center p-2.5 bg-slate-800 rounded-xl text-white">
                        <span class="text-xs font-medium text-slate-300">Kembalian</span>
                        <span class="text-base font-black text-green-400" id="displayChange">Rp 0</span>
                    </div>
                </div>

                <div class="flex gap-2 pt-0.5">
                    <input type="text" id="notesInput" placeholder="Catatan (opsional)" maxlength="500" class="flex-1 py-1.5 px-2 border border-slate-200 rounded-lg focus:outline-none focus:border-pink-400 text-[10px] text-slate-600 bg-slate-50">
                    <button id="btnSubmit" onclick="processCheckout()" disabled class="flex-1 py-2.5 rounded-xl font-bold text-sm transition-all duration-200 flex items-center justify-center gap-2 bg-slate-200 text-slate-500 cursor-not-allowed shadow-inner">
                        <i class="fas fa-check-circle"></i> Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cart = {};
        let productsData = {};
        let subtotal = 0;
        let grandTotal = 0;
        let currentCategory = 'all';
        let discount = 0;
        let taxRate = 0;
        let taxAmount = 0;
        let extraCharge = 0;
        let rounding = 0;

        document.querySelectorAll('.product-card').forEach(card => {
            const id = card.dataset.id;
            productsData[id] = {
                id: parseInt(id),
                name: card.dataset.name,
                price: parseFloat(card.dataset.price),
                stock: card.dataset.stock === '' ? null : parseInt(card.dataset.stock),
                category: parseInt(card.dataset.category)
            };
        });

        const searchInput = document.getElementById('searchInput');

        searchInput.addEventListener('input', function(e) {
            filterProducts(e.target.value.toLowerCase().trim());
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const visibleCards = document.querySelectorAll('.product-card:not(.hidden)');
                if (visibleCards.length === 1) {
                    const id = visibleCards[0].dataset.id;
                    addToCart(id);
                    searchInput.value = '';
                    filterProducts('');
                    searchInput.focus();
                }
            }
        });

        function filterProducts(term) {
            let hasVisible = false;
            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.dataset.name.toLowerCase();
                const id = card.dataset.id;
                const category = card.dataset.category;
                const matchCategory = currentCategory === 'all' || category === currentCategory;
                const matchSearch = !term || name.includes(term) || id.includes(term);
                const visible = matchCategory && matchSearch;
                card.classList.toggle('hidden', !visible);
                if (visible) hasVisible = true;
            });
            document.getElementById('noProducts').classList.toggle('hidden', hasVisible);
        }

        function filterCategory(category) {
            currentCategory = category;
            document.querySelectorAll('.category-tab').forEach(tab => {
                const isActive = tab.dataset.category === category;
                tab.classList.toggle('active', isActive);
                if (isActive) {
                    tab.className = 'category-tab active px-3 py-1.5 rounded-lg text-xs font-bold bg-pink-500 text-white';
                } else {
                    tab.className = 'category-tab px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200';
                }
            });
            filterProducts(searchInput.value.toLowerCase().trim());
        }

        function formatCurrency(num) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(num));
        }

        function addToCart(id) {
            id = id.toString();
            const product = productsData[id];
            if (!product) return;
            if (product.stock !== null && product.stock <= 0) return;

            if (cart[id]) {
                if (product.stock !== null && cart[id].qty >= product.stock) {
                    showFlash('Stok tidak mencukupi!', 'error');
                    return;
                }
                cart[id].qty++;
            } else {
                cart[id] = {
                    id: id,
                    name: product.name,
                    price: product.price,
                    qty: 1
                };
            }
            renderCart();
        }

        function updateQty(id, delta) {
            if (!cart[id]) return;
            const product = productsData[id];
            const newQty = cart[id].qty + delta;
            if (newQty <= 0) {
                delete cart[id];
            } else if (product.stock !== null && newQty > product.stock) {
                showFlash('Stok tidak mencukupi!', 'error');
                return;
            } else {
                cart[id].qty = newQty;
            }
            renderCart();
        }

        function removeCartItem(id) {
            delete cart[id.toString()];
            renderCart();
        }

        function clearCart() {
            const count = Object.keys(cart).length;
            if (count === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak Ada Item',
                    text: 'Keranjang sudah kosong.',
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
                return;
            }
            Swal.fire({
                title: 'Kosongkan Tagihan?',
                html: '<p class="text-slate-600">' + count + ' item akan dihapus dari tagihan.</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Kosongkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    cart = {};
                    document.getElementById('discountInput').value = '0';
                    document.getElementById('amountPaidInput').value = '';
                    document.getElementById('taxRateSelect').value = '0';
                    document.getElementById('extraChargeInput').value = '0';
                    document.getElementById('extraChargeLabel').value = '';
                    document.getElementById('notesInput').value = '';
                    renderCart();
                    searchInput.focus();
                    Swal.fire({
                        icon: 'success',
                        title: 'Tagihan dikosongkan',
                        timer: 1200,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }
            });
        }

        function renderCart() {
            const container = document.getElementById('cartContainer');
            const emptyState = document.getElementById('emptyCart');
            const itemIds = Object.keys(cart);
            let totalItems = 0;

            if (itemIds.length === 0) {
                container.innerHTML = '';
                container.appendChild(emptyState);
                document.getElementById('cartItemCount').innerText = '0 item';
                calculateTotal();
                return;
            }

            let html = '';
            itemIds.forEach(id => {
                const item = cart[id];
                totalItems += item.qty;
                html += `
                    <div class="cart-item bg-white p-2.5 rounded-xl shadow-sm border border-slate-100 flex items-center gap-2">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-700 text-xs truncate">${item.name}</h4>
                            <p class="text-pink-500 font-extrabold text-[11px] mt-0.5">${formatCurrency(item.price)}</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <button onclick="updateQty('${id}', -1)" class="qty-btn text-slate-400 hover:text-red-500 hover:bg-red-50">
                                <i class="fas fa-minus text-[10px]"></i>
                            </button>
                            <span class="font-black text-slate-800 text-sm min-w-[24px] text-center">${item.qty}</span>
                            <button onclick="updateQty('${id}', 1)" class="qty-btn text-slate-400 hover:text-green-500 hover:bg-green-50">
                                <i class="fas fa-plus text-[10px]"></i>
                            </button>
                            <span class="font-bold text-slate-800 text-xs ml-1">${formatCurrency(item.price * item.qty)}</span>
                            <button onclick="removeCartItem('${id}')" class="ml-1 w-6 h-6 flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-md transition-colors">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </div>`;
            });

            container.innerHTML = html;
            document.getElementById('cartItemCount').innerText = totalItems + ' item' + (totalItems > 1 ? '' : '');
            calculateTotal();
        }

        function calculateTotal() {
            subtotal = Object.values(cart).reduce((sum, item) => sum + (item.price * item.qty), 0);
            discount = parseFloat(document.getElementById('discountInput').value) || 0;
            taxRate = parseFloat(document.getElementById('taxRateSelect').value) || 0;
            extraCharge = parseFloat(document.getElementById('extraChargeInput').value) || 0;
            const extraLabel = document.getElementById('extraChargeLabel').value.trim();

            if (discount > subtotal) {
                discount = subtotal;
                document.getElementById('discountInput').value = discount;
            }

            const afterDiscount = subtotal - discount;
            taxAmount = afterDiscount * taxRate / 100;
            const beforeRounding = afterDiscount + taxAmount + extraCharge;
            grandTotal = Math.round(beforeRounding);
            rounding = grandTotal - beforeRounding;

            document.getElementById('displaySubtotal').innerText = formatCurrency(subtotal);

            const taxDetail = document.getElementById('taxDetail');
            if (taxRate > 0 && taxAmount > 0) {
                taxDetail.classList.remove('hidden');
                document.getElementById('taxRateDisplay').innerText = taxRate;
                document.getElementById('displayTax').innerText = formatCurrency(taxAmount);
            } else {
                taxDetail.classList.add('hidden');
            }

            const extraChargeDetail = document.getElementById('extraChargeDetail');
            if (extraCharge > 0) {
                extraChargeDetail.classList.remove('hidden');
                document.getElementById('extraChargeLabelDisplay').innerText = extraLabel || 'Biaya Tambahan';
                document.getElementById('displayExtraCharge').innerText = formatCurrency(extraCharge);
            } else {
                extraChargeDetail.classList.add('hidden');
            }

            const roundingDetail = document.getElementById('roundingDetail');
            if (rounding !== 0) {
                roundingDetail.classList.remove('hidden');
                document.getElementById('displayRounding').innerText = (rounding > 0 ? '' : '') + formatCurrency(rounding);
            } else {
                roundingDetail.classList.add('hidden');
            }

            document.getElementById('displayGrandTotal').innerText = formatCurrency(grandTotal);
            calculateChange();
        }

        function selectPaymentMethod(method) {
            document.querySelectorAll('.payment-method-btn').forEach(btn => {
                const isActive = btn.dataset.method === method;
                if (isActive) {
                    btn.className = 'payment-method-btn active px-2 py-2 rounded-lg text-[10px] font-bold border-2 border-pink-500 bg-pink-50 text-pink-600';
                } else {
                    btn.className = 'payment-method-btn px-2 py-2 rounded-lg text-[10px] font-bold border-2 border-slate-200 bg-white text-slate-600 hover:border-slate-300';
                }
            });

            const cashSection = document.getElementById('cashPaymentSection');
            const nonCashNotice = document.getElementById('nonCashNotice');

            if (method === 'Tunai') {
                cashSection.classList.remove('hidden');
                nonCashNotice.classList.add('hidden');
            } else {
                cashSection.classList.add('hidden');
                nonCashNotice.classList.remove('hidden');
                document.getElementById('nonCashMethod').innerText = method;
                document.getElementById('amountPaidInput').value = grandTotal;
                calculateChange();
            }
        }

        function calculateChange() {
            const amountPaid = parseFloat(document.getElementById('amountPaidInput').value) || 0;
            const change = amountPaid - grandTotal;
            const btnSubmit = document.getElementById('btnSubmit');
            const displayChange = document.getElementById('displayChange');

            if (Object.keys(cart).length === 0) {
                btnSubmit.disabled = true;
                btnSubmit.className = 'flex-1 py-2.5 rounded-xl font-bold text-sm transition-all duration-200 flex items-center justify-center gap-2 bg-slate-200 text-slate-500 cursor-not-allowed shadow-inner';
                displayChange.innerText = 'Rp 0';
                displayChange.className = 'text-base font-black text-green-400';
                return;
            }

            displayChange.innerText = formatCurrency(Math.abs(change));

            const method = document.querySelector('.payment-method-btn.active')?.dataset.method || 'Tunai';
            let canSubmit = false;

            if (method === 'Tunai') {
                canSubmit = amountPaid >= grandTotal;
            } else {
                canSubmit = amountPaid > 0;
            }

            if (canSubmit) {
                if (method === 'Tunai') {
                    displayChange.className = 'text-base font-black ' + (change >= 0 ? 'text-green-400' : 'text-red-400');
                } else {
                    displayChange.className = 'text-base font-black text-green-400';
                }
                btnSubmit.disabled = false;
                btnSubmit.className = 'flex-1 py-2.5 rounded-xl font-bold text-sm transition-all duration-200 flex items-center justify-center gap-2 bg-pink-500 hover:bg-pink-600 text-white shadow-md';
                if (method !== 'Tunai') {
                    displayChange.innerText = 'Rp 0';
                }
            } else {
                displayChange.className = 'text-base font-black text-red-400';
                btnSubmit.disabled = true;
                btnSubmit.className = 'flex-1 py-2.5 rounded-xl font-bold text-sm transition-all duration-200 flex items-center justify-center gap-2 bg-slate-200 text-slate-500 cursor-not-allowed shadow-inner';
            }
        }

        async function processCheckout() {
            const btnSubmit = document.getElementById('btnSubmit');
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...';

            const method = document.querySelector('.payment-method-btn.active')?.dataset.method || 'Tunai';
            const amountPaid = method === 'Tunai'
                ? (parseFloat(document.getElementById('amountPaidInput').value) || 0)
                : grandTotal;

            if (method === 'Tunai' && amountPaid < grandTotal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Kurang',
                    text: 'Jumlah pembayaran kurang dari total tagihan Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal)
                });
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fas fa-check-circle"></i> Bayar';
                return;
            }

            const payload = {
                items: Object.values(cart).map(i => ({ id: parseInt(i.id), qty: parseInt(i.qty) })),
                discount: discount,
                tax_rate: taxRate,
                extra_charge: extraCharge,
                extra_charge_label: document.getElementById('extraChargeLabel').value.trim() || null,
                payment_method: method,
                amount_paid: amountPaid,
                notes: document.getElementById('notesInput').value.trim() || null
            };

            try {
                const response = await fetch("{{ route('kasir.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(payload)
                });

                const responseText = await response.text();
                let data;
                try {
                    data = JSON.parse(responseText);
                } catch (e) {
                    console.error('Invalid JSON:', responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Server',
                        text: responseText.substring(0, 200) || 'Terjadi kesalahan server. Lihat console.'
                    });
                    throw new Error('Invalid JSON');
                }

                if (response.ok && data.success) {
                    showFlash('Pembayaran berhasil!', 'success');
                    Swal.fire({
                        icon: 'success',
                        title: 'Transaksi Berhasil',
                        html: '<div class="text-center"><p class="text-lg font-bold text-slate-800">' + (data.invoice_number || '') + '</p><p class="text-sm text-slate-500 mt-1">Pembayaran ' + method + '</p><p class="text-2xl font-black text-green-600 mt-2">' + new Intl.NumberFormat('id-ID').format(grandTotal) + '</p></div>',
                        showConfirmButton: true,
                        confirmButtonText: '<i class="fas fa-print mr-1"></i> Cetak Struk',
                        showCancelButton: true,
                        cancelButtonText: 'Tutup',
                        confirmButtonColor: '#ec4899',
                        cancelButtonColor: '#64748b'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open("{{ url('/admin/kasir/pos/receipt') }}/" + data.transaction_id, '_blank', 'width=420,height=700');
                        }
                        cart = {};
                        document.getElementById('discountInput').value = '0';
                        document.getElementById('amountPaidInput').value = '';
                        document.getElementById('taxRateSelect').value = '0';
                        document.getElementById('extraChargeInput').value = '0';
                        document.getElementById('extraChargeLabel').value = '';
                        document.getElementById('notesInput').value = '';
                        renderCart();
                        searchInput.focus();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Gagal memproses transaksi.'
                    });
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = '<i class="fas fa-check-circle"></i> Bayar';
                }
            } catch (error) {
                console.error('Checkout Error:', error);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal terhubung ke server.' });
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fas fa-check-circle"></i> Bayar';
            }
        }

        function setQuickCash(amount) {
            const input = document.getElementById('amountPaidInput');
            input.value = (parseInt(input.value) || 0) + amount;
            input.focus();
            calculateChange();
        }

        function setExact() {
            document.getElementById('amountPaidInput').value = grandTotal;
            document.getElementById('amountPaidInput').focus();
            calculateChange();
        }

        function showFlash(message, type) {
            Swal.fire({
                icon: type === 'success' ? 'success' : 'error',
                title: message,
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'F1') {
                e.preventDefault();
                searchInput.focus();
                searchInput.select();
            } else if (e.key === 'F2') {
                e.preventDefault();
                document.getElementById('discountInput').focus();
                document.getElementById('discountInput').select();
            } else if (e.key === 'F3') {
                e.preventDefault();
                document.getElementById('amountPaidInput').focus();
                document.getElementById('amountPaidInput').select();
            } else if (e.key === 'F5') {
                e.preventDefault();
                clearCart();
            } else if (e.key === 'F8') {
                e.preventDefault();
                const btn = document.getElementById('btnSubmit');
                if (!btn.disabled) processCheckout();
            } else if (e.key === 'Escape') {
                if (Object.keys(cart).length > 0) {
                    e.preventDefault();
                    clearCart();
                }
            }
        });

        searchInput.focus();

        // Sidebar Toggle Logic for POS
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('sidebarCollapse');

            function toggleSidebar() {
                if (window.innerWidth < 1024) { // lg breakpoint
                    if (sidebar.classList.contains('-translate-x-full')) {
                        // Open sidebar
                        sidebar.classList.remove('-translate-x-full');
                        sidebar.classList.add('translate-x-0');
                        overlay.classList.remove('hidden');
                        setTimeout(() => overlay.classList.add('opacity-100'), 10);
                    } else {
                        // Close sidebar
                        sidebar.classList.add('-translate-x-full');
                        sidebar.classList.remove('translate-x-0');
                        overlay.classList.remove('opacity-100');
                        setTimeout(() => overlay.classList.add('hidden'), 300);
                    }
                }
            }

            if(toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
            if(overlay) overlay.addEventListener('click', toggleSidebar);
        });
    </script>
</body>
</html>
