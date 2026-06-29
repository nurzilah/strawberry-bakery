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
}
