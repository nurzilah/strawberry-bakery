<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesin POS Kasir - Strawberry Bakery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        #sidebar ul li a:hover {
            color: #d63384;
            background: rgba(255,182,193,0.2);
            border-radius: 0 25px 25px 0;
            margin-right: 15px;
        }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 antialiased">

    <nav id="sidebar" style="min-width:250px;max-width:250px;background:#fff;border-right:1px solid #dee2e6;height:100vh;overflow-y:auto;position:sticky;top:0;z-index:1000;display:flex;flex-direction:column;">
        <div style="padding: 16px; border-bottom: 1px solid #dee2e6; display: flex; align-items: center; gap: 12px;">
            <img src="{{ asset('images/logo.png') }}" alt="SB" style="width: 40px; height: 40px; object-fit: contain;">
            @php
                $role = auth()->user()->role ?? 'guest';
                if(in_array($role, ['owner', 'superadmin'])) {
                    $bg = '#d63384';
                    $label = 'OWNER';
                } elseif($role === 'admin') {
                    $bg = '#0d6efd';
                    $label = 'ADMIN';
                } elseif($role === 'kasir') {
                    $bg = '#198754';
                    $label = 'KASIR';
                } else {
                    $bg = '#6c757d';
                    $label = strtoupper($role);
                }
            @endphp
            <div style="overflow: hidden; text-align: left;">
                <div style="font-weight: 700; font-size: 0.95rem; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.2;">
                    {{ auth()->user()->name }}
                </div>
                <div style="margin-top: 4px;">
                    <span style="background-color: {{ $bg }}; color: white; font-size: 0.65rem; padding: 0.35em 0.6em; border-radius: 4px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase;">
                        {{ $label }}
                    </span>
                </div>
            </div>
        </div>

        <ul style="list-style:none;padding:20px 0;margin:0;flex:1;">
            @if(in_array(auth()->user()->role, ['owner','superadmin']))
                <li>
                    <a href="{{ route('admin.dashboard') }}" style="display:block;padding:12px 20px;color:#495057;text-decoration:none;font-size:1em;transition:0.2s;">
                        <i class="fas fa-chart-pie" style="margin-right:8px;width:20px;text-align:center;"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products.index') }}" style="display:block;padding:12px 20px;color:#495057;text-decoration:none;font-size:1em;transition:0.2s;">
                        <i class="fas fa-box-open" style="margin-right:8px;width:20px;text-align:center;"></i> Kelola Produk
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" style="display:block;padding:12px 20px;color:#495057;text-decoration:none;font-size:1em;transition:0.2s;">
                        <i class="fas fa-tags" style="margin-right:8px;width:20px;text-align:center;"></i> Kelola Kategori
                    </a>
                </li>
                <li style="background:rgba(255,182,193,0.3);border-radius:0 25px 25px 0;margin-right:15px;">
                    <a href="{{ route('kasir.pos') }}" style="display:block;padding:12px 20px;color:#d63384;text-decoration:none;font-size:1em;font-weight:600;">
                        <i class="fas fa-cash-register" style="margin-right:8px;width:20px;text-align:center;"></i> Mesin POS / Kasir
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders.index') }}" style="display:block;padding:12px 20px;color:#495057;text-decoration:none;font-size:1em;transition:0.2s;">
                        <i class="fas fa-shopping-cart" style="margin-right:8px;width:20px;text-align:center;"></i> Pesanan Online
                    </a>
                </li>
                <li>
                    <a href="{{ route('kasir.transactions.index') }}" style="display:block;padding:12px 20px;color:#495057;text-decoration:none;font-size:1em;transition:0.2s;">
                        <i class="fas fa-history" style="margin-right:8px;width:20px;text-align:center;"></i> Riwayat Transaksi
                    </a>
                </li>
                <li>
                    <a href="{{ route('owner.users.index') }}" style="display:block;padding:12px 20px;color:#495057;text-decoration:none;font-size:1em;transition:0.2s;">
                        <i class="fas fa-users" style="margin-right:8px;width:20px;text-align:center;"></i> Manajemen Akun
                    </a>
                </li>
            @elseif(auth()->user()->role === 'admin')
                <li>
                    <a href="{{ route('admin.dashboard') }}" style="display:block;padding:12px 20px;color:#495057;text-decoration:none;font-size:1em;">
                        <i class="fas fa-chart-pie" style="margin-right:8px;width:20px;text-align:center;"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products.index') }}" style="display:block;padding:12px 20px;color:#495057;text-decoration:none;font-size:1em;">
                        <i class="fas fa-box-open" style="margin-right:8px;width:20px;text-align:center;"></i> Kelola Produk
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" style="display:block;padding:12px 20px;color:#495057;text-decoration:none;font-size:1em;">
                        <i class="fas fa-tags" style="margin-right:8px;width:20px;text-align:center;"></i> Kelola Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders.index') }}" style="display:block;padding:12px 20px;color:#495057;text-decoration:none;font-size:1em;">
                        <i class="fas fa-shopping-cart" style="margin-right:8px;width:20px;text-align:center;"></i> Pesanan Online
                    </a>
                </li>
            @elseif(auth()->user()->role === 'kasir')
                <li style="background:rgba(255,182,193,0.3);border-radius:0 25px 25px 0;margin-right:15px;">
                    <a href="{{ route('kasir.pos') }}" style="display:block;padding:12px 20px;color:#d63384;text-decoration:none;font-size:1em;font-weight:600;">
                        <i class="fas fa-cash-register" style="margin-right:8px;width:20px;text-align:center;"></i> Mesin POS / Kasir
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders.index') }}" style="display:block;padding:12px 20px;color:#495057;text-decoration:none;font-size:1em;">
                        <i class="fas fa-shopping-cart" style="margin-right:8px;width:20px;text-align:center;"></i> Update Pesanan
                    </a>
                </li>
                <li>
                    <a href="{{ route('kasir.transactions.index') }}" style="display:block;padding:12px 20px;color:#495057;text-decoration:none;font-size:1em;">
                        <i class="fas fa-history" style="margin-right:8px;width:20px;text-align:center;"></i> Riwayat Transaksi
                    </a>
                </li>
            @endif
        </ul>

        <div style="padding:16px;border-top:1px solid #dee2e6;background:transparent;margin-top:auto;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="width:100%;padding:10px;background:none;border:1px solid #dee2e6;border-radius:8px;color:#dc3545;cursor:pointer;font-size:13px;font-family:inherit;transition:0.2s;display:flex;align-items:center;justify-content:center;gap:6px;" onmouseover="this.style.background='rgba(220,53,69,0.1)'" onmouseout="this.style.background='none'">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>


    </nav>

    <div class="flex-1 flex flex-row w-full h-full overflow-hidden">

        <div class="w-[70%] h-full flex flex-col border-r border-slate-200 bg-white">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between shadow-sm z-10">
                <h1 class="text-xl font-bold text-slate-800 flex items-center"><i class="fas fa-cash-register text-pink-500 mr-2"></i> Terminal POS</h1>
                <div class="relative w-1/2">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="searchInput" placeholder="Cari nama produk..." class="w-full pl-11 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-400/20 transition-all text-sm font-medium">
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 bg-slate-50">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="productGrid">
                    @foreach($products as $product)
                        <div class="product-card group relative bg-white rounded-xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer border border-slate-100 overflow-hidden flex flex-col"
                             data-id="{{ $product->id }}"
                             data-name="{{ $product->name }}"
                             data-price="{{ $product->price }}"
                             data-stock="{{ $product->stock }}"
                             onclick="addToCart({{ $product->id }})">
                            <div class="h-32 w-full bg-slate-100 relative overflow-hidden">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $product->name }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <i class="fas fa-image fa-2x"></i>
                                    </div>
                                @endif
                                <div class="absolute top-2 right-2 bg-white/90 backdrop-blur text-[10px] font-bold px-2 py-1 rounded-full shadow-sm text-slate-700">
                                    Sisa: <span class="text-pink-600 stock-display">{{ $product->stock }}</span>
                                </div>
                            </div>
                            <div class="p-3 flex-1 flex flex-col justify-between">
                                <h3 class="font-bold text-slate-700 text-xs line-clamp-2 product-name leading-tight mb-1">{{ $product->name }}</h3>
                                <p class="text-pink-500 font-extrabold text-xs">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[1px] flex items-center justify-center z-20 transition-opacity duration-200 {{ $product->stock > 0 ? 'opacity-0 pointer-events-none' : 'opacity-100' }}" id="overlay-{{ $product->id }}">
                                <span class="bg-red-500 text-white text-xs font-bold px-4 py-1 rounded-full shadow border border-white tracking-wider transform -rotate-12">HABIS</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="w-[30%] h-full flex flex-col bg-white relative z-20 shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.03)]">

            <div class="p-4 border-b border-slate-100 bg-white flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-800">Tagihan Saat Ini</h2>
                <span class="bg-pink-100 text-pink-600 text-[11px] font-bold px-2.5 py-0.5 rounded-full" id="cartItemCount">0 Item</span>
            </div>

            <div class="flex-1 overflow-y-auto p-3 space-y-2 bg-slate-50/50" id="cartContainer">
                <div id="emptyCart" class="h-full flex flex-col items-center justify-center text-slate-400 py-12">
                    <i class="fas fa-shopping-basket fa-3x mb-3 opacity-30 text-slate-400"></i>
                    <p class="font-semibold text-xs">Belum ada pesanan</p>
                    <p class="text-[11px] mt-0.5 text-slate-400">Klik produk untuk menambahkan</p>
                </div>
            </div>

            <div class="p-4 bg-white border-t border-slate-200 shadow-[0_-8px_20px_rgba(0,0,0,0.04)] space-y-3">
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between text-slate-600 font-medium">
                        <span>Subtotal</span>
                        <span id="displaySubtotal" class="font-semibold">Rp 0</span>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="text-slate-600 font-medium">Diskon (Rp)</span>
                        <input type="number" id="discountInput" value="0" min="0" class="w-1/2 text-right py-1.5 px-3 border border-slate-200 rounded-lg focus:outline-none focus:border-pink-400 text-slate-800 font-bold text-xs" oninput="calculateTotal()">
                    </div>

                    <div class="flex justify-between items-center py-2 border-t border-dashed border-slate-200">
                        <span class="text-sm font-bold text-slate-700">Total Tagihan</span>
                        <span class="text-xl font-black text-pink-600" id="displayGrandTotal">Rp 0</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Metode Bayar</label>
                            <select id="paymentMethod" class="w-full py-1.5 px-2 border border-slate-200 rounded-lg focus:outline-none focus:border-pink-400 text-xs font-semibold text-slate-700 bg-slate-50">
                                <option value="Tunai">Uang Tunai</option>
                                <option value="Transfer">Transfer Bank</option>
                                <option value="QRIS">QRIS</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Jumlah Bayar (Rp)</label>
                            <input type="number" id="amountPaidInput" placeholder="0" class="w-full py-1 px-2 border border-slate-300 rounded-lg focus:outline-none focus:border-pink-500 text-sm font-black text-slate-800 text-right bg-slate-50" oninput="calculateChange()">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-1.5 pt-0.5">
                        <button onclick="setQuickCash(5000)"   class="py-1 bg-slate-100 hover:bg-pink-100 hover:text-pink-600 text-[10px] font-bold rounded transition-colors">5.000</button>
                        <button onclick="setQuickCash(10000)"  class="py-1 bg-slate-100 hover:bg-pink-100 hover:text-pink-600 text-[10px] font-bold rounded transition-colors">10.000</button>
                        <button onclick="setQuickCash(20000)"  class="py-1 bg-slate-100 hover:bg-pink-100 hover:text-pink-600 text-[10px] font-bold rounded transition-colors">20.000</button>
                        <button onclick="setQuickCash(50000)"  class="py-1 bg-slate-100 hover:bg-pink-100 hover:text-pink-600 text-[10px] font-bold rounded transition-colors">50.000</button>
                        <button onclick="setQuickCash(100000)" class="py-1 bg-slate-100 hover:bg-pink-100 hover:text-pink-600 text-[10px] font-bold rounded transition-colors">100.000</button>
                        <button onclick="setExact()"           class="py-1 bg-pink-500 hover:bg-pink-600 text-white text-[10px] font-bold rounded transition-colors">Pas</button>
                    </div>

                    <div class="flex justify-between items-center p-2.5 bg-slate-900 rounded-lg text-white mt-2">
                        <span class="text-xs font-medium text-slate-400">Kembalian</span>
                        <span class="text-base font-black text-green-400" id="displayChange">Rp 0</span>
                    </div>
                </div>

                <button id="btnSubmit" onclick="processCheckout()" disabled class="w-full py-3 rounded-xl font-bold text-sm transition-all duration-200 flex items-center justify-center gap-2 bg-slate-200 text-slate-500 cursor-not-allowed shadow-inner">
                    <i class="fas fa-check-circle"></i> Selesaikan Pembayaran
                </button>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        let cart = {};
        let productsData = {};

        // Mapping Data Awal dari Blade Loop ke Object JavaScript
        document.querySelectorAll('.product-card').forEach(card => {
            const id = card.dataset.id;
            productsData[id] = {
                id: parseInt(id),
                name: card.dataset.name,
                price: parseFloat(card.dataset.price),
                stock: parseInt(card.dataset.stock)
            };
        });

        // Live Real-Time Search Filter
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase().trim();
            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.querySelector('.product-name').innerText.toLowerCase();
                card.classList.toggle('hidden', !name.includes(term));
            });
        });

        function formatCurrency(num) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
        }

        function addToCart(id) {
            const product = productsData[id];
            if (product.stock <= 0) return;

            if (cart[id]) {
                if (cart[id].qty < product.stock) {
                    cart[id].qty++;
                } else {
                    alert('Batas Stok Maksimal Terlampaui!');
                    return;
                }
            } else {
                cart[id] = { ...product, qty: 1 };
            }
            renderCart();
        }

        function updateQty(id, delta) {
            if (!cart[id]) return;
            const newQty = cart[id].qty + delta;
            if (newQty <= 0) {
                delete cart[id];
            } else if (newQty > productsData[id].stock) {
                alert('Batas Stok Maksimal Terlampaui!');
            } else {
                cart[id].qty = newQty;
            }
            renderCart();
        }

        function removeCartItem(id) {
            delete cart[id];
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cartContainer');
            const emptyState = document.getElementById('emptyCart');
            const itemIds = Object.keys(cart);
            let totalItems = 0;

            if (itemIds.length === 0) {
                emptyState.style.display = 'flex';
                container.innerHTML = '';
                container.appendChild(emptyState);
                document.getElementById('cartItemCount').innerText = '0 Item';
                calculateTotal();
                return;
            }

            emptyState.style.display = 'none';
            let html = '';
            itemIds.forEach(id => {
                const item = cart[id];
                totalItems += item.qty;
                html += `
                    <div class="bg-white p-3 rounded-xl shadow-sm border border-slate-100 flex items-center justify-between text-xs mb-2">
                        <div class="flex-1 pr-2">
                            <h4 class="font-bold text-slate-700 line-clamp-1">${item.name}</h4>
                            <p class="text-pink-500 font-extrabold mt-0.5">${formatCurrency(item.price * item.qty)}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex items-center bg-slate-100 rounded-lg border border-slate-200 p-0.5">
                                <button onclick="updateQty(${id}, -1)" class="w-6 h-6 rounded bg-white text-slate-600 shadow-sm hover:text-pink-600 flex items-center justify-center"><i class="fas fa-minus text-[10px]"></i></button>
                                <span class="w-6 text-center font-bold text-slate-700">${item.qty}</span>
                                <button onclick="updateQty(${id}, 1)" class="w-6 h-6 rounded bg-white text-slate-600 shadow-sm hover:text-pink-600 flex items-center justify-center"><i class="fas fa-plus text-[10px]"></i></button>
                            </div>
                            <button onclick="removeCartItem(${id})" class="w-7 h-7 flex items-center justify-center text-slate-300 hover:bg-red-50 hover:text-red-500 rounded-md transition-colors">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                    </div>`;
            });

            container.innerHTML = html;
            document.getElementById('cartItemCount').innerText = totalItems + ' Item';
            calculateTotal();
        }

        let subtotal = 0;
        let grandTotal = 0;

        function calculateTotal() {
            subtotal = Object.values(cart).reduce((sum, item) => sum + (item.price * item.qty), 0);
            document.getElementById('displaySubtotal').innerText = formatCurrency(subtotal);
            let discount = parseFloat(document.getElementById('discountInput').value) || 0;
            grandTotal = Math.max(subtotal - discount, 0);
            document.getElementById('displayGrandTotal').innerText = formatCurrency(grandTotal);
            calculateChange();
        }

        function calculateChange() {
            const amountPaid = parseFloat(document.getElementById('amountPaidInput').value) || 0;
            const change = amountPaid - grandTotal;
            const btnSubmit = document.getElementById('btnSubmit');
            const displayChange = document.getElementById('displayChange');

            if (Object.keys(cart).length === 0) {
                btnSubmit.disabled = true;
                btnSubmit.className = "w-full py-3 rounded-xl font-bold text-sm transition-all duration-200 flex items-center justify-center gap-2 bg-slate-200 text-slate-500 cursor-not-allowed";
                displayChange.innerText = 'Rp 0';
                return;
            }

            displayChange.innerText = formatCurrency(change);
            if (amountPaid >= grandTotal) {
                displayChange.style.color = '#4ade80';
                btnSubmit.disabled = false;
                btnSubmit.className = "w-full py-3 rounded-xl font-bold text-sm transition-all duration-200 flex items-center justify-center gap-2 bg-pink-500 hover:bg-pink-600 text-white shadow-md cursor-pointer";
            } else {
                displayChange.style.color = '#f87171';
                btnSubmit.disabled = true;
                btnSubmit.className = "w-full py-3 rounded-xl font-bold text-sm transition-all duration-200 flex items-center justify-center gap-2 bg-slate-200 text-slate-500 cursor-not-allowed";
            }
        }

        async function processCheckout() {
            const btnSubmit = document.getElementById('btnSubmit');
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...';

            const payload = {
                items: Object.values(cart).map(i => ({ id: i.id, qty: i.qty })),
                discount: parseFloat(document.getElementById('discountInput').value) || 0,
                payment_method: document.getElementById('paymentMethod').value,
                amount_paid: parseFloat(document.getElementById('amountPaidInput').value) || 0
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
                    console.error("Invalid JSON response:", responseText);
                    alert("Error dari server: " + response.status + " " + response.statusText + "\nCek console untuk detail.");
                    throw new Error("Invalid JSON");
                }

                if (response.ok && data.success) {
                    alert('Pembayaran Berhasil Diproses!');
                    const baseUrl = window.location.origin;
                    window.open(`${baseUrl}/admin/kasir/pos/receipt/${data.transaction_id}`, '_blank');
                    window.location.reload();
                } else {
                    alert('Gagal Memproses Transaksi: ' + (data.message || 'Unknown error'));
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = '<i class="fas fa-check-circle"></i> Selesaikan Pembayaran';
                }
            } catch (error) {
                console.error("Checkout Error:", error);
                alert('Terjadi kesalahan komunikasi data ke server. Cek console log.');
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fas fa-check-circle"></i> Selesaikan Pembayaran';
            }
        }

        function setQuickCash(amount) {
            const input = document.getElementById('amountPaidInput');
            input.value = (parseInt(input.value) || 0) + amount;
            calculateChange();
        }

        function setExact() {
            document.getElementById('amountPaidInput').value = grandTotal;
            calculateChange();
        }
    </script>
</body>
</html>
