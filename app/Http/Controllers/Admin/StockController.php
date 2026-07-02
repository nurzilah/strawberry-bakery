<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of all stock histories.
     */
    public function index(Request $request)
    {
        $query = StockHistory::with(['product', 'user'])->latest();

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('month')) {
            $month = date('m', strtotime($request->month));
            $year = date('Y', strtotime($request->month));
            $query->whereMonth('created_at', $month)
                  ->whereYear('created_at', $year);
        }

        $histories = $query->paginate(15)->withQueryString();
        
        $products = Product::orderBy('name')->get();

        // Chart Data: Total stock added per month (last 12 months)
        $chartData = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $totalAdded = StockHistory::whereMonth('created_at', $date->month)
                                      ->whereYear('created_at', $date->year)
                                      ->sum('quantity_added');
            
            $chartData->push([
                'month' => $date->translatedFormat('M Y'),
                'total' => $totalAdded
            ]);
        }

        return view('admin.stock.index', compact('histories', 'products', 'chartData'));
    }

    /**
     * Show the form for creating a new stock addition.
     */
    public function create(Product $product)
    {
        return view('admin.stock.add', compact('product'));
    }

    /**
     * Store a newly created stock addition in storage.
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'quantity_added' => [
                'required',
                'integer',
                'not_in:0',
                function ($attribute, $value, $fail) use ($product) {
                    if ($product->stock + $value < 0) {
                        $fail('Stok akhir tidak boleh kurang dari 0. Stok saat ini: ' . $product->stock);
                    }
                }
            ],
            'notes' => 'nullable|string|max:255',
        ]);

        $product->addStock(
            $request->quantity_added,
            $request->notes,
            auth()->id()
        );

        $action = $request->quantity_added > 0 ? 'ditambahkan' : 'dikurangi';
        return redirect()->route('admin.products.index')
                         ->with('success', 'Stok untuk produk ' . $product->name . ' berhasil ' . $action . '.');
    }

    /**
     * Display the specified stock history for a product.
     */
    public function history(Product $product)
    {
        $histories = $product->stockHistories()->with('user')->latest()->paginate(15);
        
        return view('admin.stock.history', compact('product', 'histories'));
    }
}
