<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'items'])->latest();

        // Kasir dapat melihat semua riwayat transaksi (filter user_id dihapus agar tidak terlalu ketat)

        // Filter tanggal dari
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // Filter tanggal sampai
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter metode pembayaran
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $transactions = $query->paginate(15)->withQueryString();

        return view('kasir.transactions.index', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = Transaction::with(['user', 'items'])->findOrFail($id);
        
        // Proteksi dihilangkan agar kasir dapat melihat semua riwayat.

        return view('kasir.transactions.show', compact('transaction'));
    }

    public function destroy($id)
    {
        if (!in_array(auth()->user()->role, ['owner', 'superadmin', 'admin'])) {
            abort(403, 'Akses Ditolak. Hanya admin/owner yang dapat membatalkan transaksi kasir.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($id) {
                $transaction = Transaction::with('items')->findOrFail($id);
                
                if ($transaction->status === 'refunded' || $transaction->status === 'cancelled') {
                    throw new \Exception("Transaksi ini sudah dibatalkan sebelumnya.");
                }

                // Kembalikan stok produk
                foreach ($transaction->items as $item) {
                    $product = \App\Models\Product::where('id', $item->product_id)->lockForUpdate()->first();
                    if ($product) {
                        $product->increment('stock', $item->qty);
                    }
                }
                
                $transaction->update(['status' => 'refunded']);
            });

            return redirect()->route('kasir.transactions.index')->with('success', 'Data transaksi berhasil dibatalkan (Refund) dan stok dikembalikan.');
        } catch (\Exception $e) {
            return redirect()->route('kasir.transactions.index')->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
