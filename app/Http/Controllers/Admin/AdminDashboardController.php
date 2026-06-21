<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Total Penjualan
        $todaySales = Order::whereDate('created_at', $today)->whereNotIn('status', ['cancelled'])->sum('total_amount');
        $monthSales = Order::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->whereNotIn('status', ['cancelled'])->sum('total_amount');

        // Totals
        $orderCount = Order::count();
        $productCount = Product::count();
        $customerCount = User::where('role', 'customer')->count();

        // Produk Terlaris
        $bestSellingProducts = Product::withCount(['orderItems as total_sold' => function($query) {
            $query->select(DB::raw('SUM(quantity)'));
        }])->orderByDesc('total_sold')->take(5)->get();

        // Pesanan Terbaru
        $latestOrders = Order::with('user')->latest()->take(5)->get();

        // Grafik Penjualan Harian (7 Hari Terakhir)
        $dailySales = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->whereNotIn('status', ['cancelled'])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();
            
        $dailySalesLabels = [];
        $dailySalesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dailySalesLabels[] = Carbon::now()->subDays($i)->format('d M');
            $sale = $dailySales->firstWhere('date', $date);
            $dailySalesData[] = $sale ? $sale->total : 0;
        }

        // Grafik Penjualan Bulanan (6 Bulan Terakhir)
        $monthlySales = Order::select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('SUM(total_amount) as total'))
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->whereNotIn('status', ['cancelled'])
            ->groupBy('year', 'month')
            ->orderBy('year', 'ASC')
            ->orderBy('month', 'ASC')
            ->get();

        $monthlySalesLabels = [];
        $monthlySalesData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlySalesLabels[] = $month->format('M Y');
            $sale = $monthlySales->first(function($item) use ($month) {
                return $item->month == $month->month && $item->year == $month->year;
            });
            $monthlySalesData[] = $sale ? $sale->total : 0;
        }

        // Stok Menipis (Misal < 10)
        $lowStockProducts = Product::where('stock', '<=', 10)->orderBy('stock', 'asc')->take(5)->get();

        // Sistem Rekomendasi (Dummy atau Actual Stats jika ada tabelnya, disini kita buat dummy/placeholder untuk views)
        $recommendationStats = [
            'usage_count' => rand(100, 500),
            'top_recommended' => Product::inRandomOrder()->take(3)->get(),
            'accuracy' => 85.5,
            'precision' => 82.3,
            'recall' => 88.1,
            'mae' => 1.2,
            'rmse' => 1.5,
        ];

        return view('admin.dashboard', compact(
            'todaySales', 'monthSales', 'orderCount', 'productCount', 'customerCount',
            'bestSellingProducts', 'latestOrders', 
            'dailySalesLabels', 'dailySalesData',
            'monthlySalesLabels', 'monthlySalesData',
            'lowStockProducts', 'recommendationStats'
        ));
    }
}
