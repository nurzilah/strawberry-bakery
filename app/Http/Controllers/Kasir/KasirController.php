<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->get();
        return view('kasir.pos', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'discount' => 'numeric|min:0',
            'payment_method' => 'required|string',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        try {
            $transaction = DB::transaction(function () use ($request) {
                $total = 0;
                $itemsData = [];

                foreach ($request->items as $item) {
                    $product = Product::where('id', $item['id'])->lockForUpdate()->first();

                    if ($product->stock < $item['qty']) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi. Sisa: {$product->stock}");
                    }

                    $subtotal = $product->price * $item['qty'];
                    $total += $subtotal;

                    $product->decrement('stock', $item['qty']);

                    $itemsData[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'qty' => $item['qty'],
                        'subtotal' => $subtotal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                $discount = $request->discount ?? 0;
                $grandTotal = $total - $discount;
                if ($grandTotal < 0) $grandTotal = 0;

                $amountPaid = $request->amount_paid;
                $amountChange = $amountPaid - $grandTotal;

                if ($amountPaid < $grandTotal) {
                    throw new \Exception("Uang pembayaran kurang.");
                }

                $transactionId = DB::table('transactions')->insertGetId([
                    'user_id' => auth()->id(),
                    'total' => $total,
                    'discount' => $discount,
                    'grand_total' => $grandTotal,
                    'payment_method' => $request->payment_method,
                    'amount_paid' => $amountPaid,
                    'amount_change' => $amountChange,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($itemsData as &$data) {
                    $data['transaction_id'] = $transactionId;
                }

                DB::table('transaction_items')->insert($itemsData);

                return $transactionId;
            });

            return response()->json([
                'success' => true,
                'transaction_id' => $transaction,
                'message' => 'Transaksi berhasil disimpan.'
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('POS Checkout Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 422);
        }
    }

    public function printReceipt($id)
    {
        $transaction = DB::table('transactions')->where('id', $id)->first();
        if (!$transaction) abort(404);

        $items = DB::table('transaction_items')->where('transaction_id', $id)->get();
        $user = DB::table('users')->where('id', $transaction->user_id)->first();

        return view('kasir.receipt', compact('transaction', 'items', 'user'));
    }
}
