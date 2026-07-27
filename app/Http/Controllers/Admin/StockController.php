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
    public function create(Request $request)
    {
        $products = Product::orderBy('name')->get();
        $selectedProductId = $request->product_id ?? old('product_id');
        return view('admin.stock.add', compact('products', 'selectedProductId'));
    }

    /**
     * Store a newly created stock addition in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_added' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ], [
            'quantity_added.min' => 'Jumlah penambahan minimal adalah 1.',
        ]);

        $product = Product::findOrFail($request->product_id);

        $product->addStock(
            $request->quantity_added,
            $request->notes,
            auth()->id()
        );

        return redirect()->route('admin.stock.index')
                         ->with('success', 'Stok untuk produk ' . $product->name . ' berhasil ditambahkan.');
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
