<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Calculate total gross revenue/sales volume for the current month
        $totalSales = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->whereIn('status', ['processing', 'completed'])
            ->sum('total_amount');

        // Count the current real-time queue size for active orders
        $activeQueueSize = Order::whereIn('status', ['pending', 'waiting_verification'])->count();

        // Fetch the latest 5 successful bakery customer transactions
        $recentSuccessfulOrders = Order::with('user')
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('owner.dashboard', compact(
            'totalSales', 
            'activeQueueSize', 
            'recentSuccessfulOrders'
        ));
    }
}
