<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Helper filter order online yang sah (bukan dibatalkan dan sudah lunas/sukses/diproses)
        $validOrders = function ($q) {
            $q->whereNotIn('status', ['cancelled'])
              ->where(function ($sub) {
                  $sub->whereIn('payment_status', ['paid', 'success', 'settlement'])
                      ->orWhereIn('status', ['completed', 'processing', 'waiting_verification']);
              });
        };

        // Total Penjualan (Online + Kasir POS)
        $todayOnlineSales = Order::whereDate('created_at', $today)->where($validOrders)->sum('total_amount');
        $todayPosSales = Transaction::whereDate('created_at', $today)->where('status', 'completed')->sum('grand_total');
        $todaySales = $todayOnlineSales + $todayPosSales;

        $monthOnlineSales = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where($validOrders)
            ->sum('total_amount');
        $monthPosSales = Transaction::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('status', 'completed')
            ->sum('grand_total');
        $monthSales = $monthOnlineSales + $monthPosSales;

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

        // Grafik Penjualan Harian (7 Hari Terakhir: Online + POS)
        $dailyOnline = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->where($validOrders)
            ->groupBy('date')
            ->get()->keyBy('date');

        $dailyPos = Transaction::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(grand_total) as total'))
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->where('status', 'completed')
            ->groupBy('date')
            ->get()->keyBy('date');
            
        $dailySalesLabels = [];
        $dailySalesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dailySalesLabels[] = Carbon::now()->subDays($i)->format('d M');
            $online = $dailyOnline->has($date) ? $dailyOnline[$date]->total : 0;
            $pos = $dailyPos->has($date) ? $dailyPos[$date]->total : 0;
            $dailySalesData[] = $online + $pos;
        }

        // Grafik Penjualan Bulanan (6 Bulan Terakhir: Online + POS)
        $monthlyOnline = Order::select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('SUM(total_amount) as total'))
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->where($validOrders)
            ->groupBy('year', 'month')
            ->get();

        $monthlyPos = Transaction::select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('SUM(grand_total) as total'))
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->where('status', 'completed')
            ->groupBy('year', 'month')
            ->get();

        $monthlySalesLabels = [];
        $monthlySalesData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlySalesLabels[] = $month->format('M Y');
            $online = $monthlyOnline->first(function($item) use ($month) {
                return $item->month == $month->month && $item->year == $month->year;
            });
            $pos = $monthlyPos->first(function($item) use ($month) {
                return $item->month == $month->month && $item->year == $month->year;
            });
            $monthlySalesData[] = ($online ? $online->total : 0) + ($pos ? $pos->total : 0);
        }

        // Stok Menipis (Misal < 10)
        $lowStockProducts = Product::where('stock', '<=', 10)->orderBy('stock', 'asc')->take(5)->get();

        $recommendationService = app(\App\Services\RecommendationService::class);
        
        // Evaluasi dari beberapa user pembeli aktif untuk metrik yang valid
        $testUsers = User::whereHas('orders', function ($q) {
            $q->where('status', 'completed');
        })->take(10)->pluck('id');

        $totalRecall = 0;
        $totalMAE = 0;
        $totalRMSE = 0;
        $userCount = $testUsers->count();

        foreach ($testUsers as $uid) {
            $totalRecall += $recommendationService->calculateRecall($uid);
            $totalMAE += $recommendationService->calculateMAE($uid);
            $totalRMSE += $recommendationService->calculateRMSE($uid);
        }

        $avgRecall = $userCount > 0 ? ($totalRecall / $userCount) : 0;
        $avgMAE = $userCount > 0 ? ($totalMAE / $userCount) : 0;
        $avgRMSE = $userCount > 0 ? ($totalRMSE / $userCount) : 0;

        $precision = $recommendationService->calculatePrecision();

        $recommendationStats = [
            'usage_count' => DB::table('recommendation_logs')->count(),
            'accuracy' => number_format($precision, 2),
            'precision' => number_format($precision, 2),
            'recall' => number_format($avgRecall, 2),
            'mae' => number_format($avgMAE, 4),
            'rmse' => number_format($avgRMSE, 4),
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
