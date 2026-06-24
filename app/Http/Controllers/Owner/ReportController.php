<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'today');
        
        $startDate = Carbon::today();
        $endDate = Carbon::today()->endOfDay();

        if ($period === 'week') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } elseif ($period === 'month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } elseif ($period === 'custom' && $request->filled(['start_date', 'end_date'])) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
        }

        // Stats
        $totalOnline = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
            
        $totalPos = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->sum('grand_total');

        $totalCombined = $totalOnline + $totalPos;

        // Daily Recap (Online)
        $dailyOnline = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->get()->keyBy('date');

        // Daily Recap (POS)
        $dailyPos = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(grand_total) as total'))
            ->groupBy('date')
            ->get()->keyBy('date');

        // Merge daily recap
        $dates = collect();
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $online = $dailyOnline->has($dateStr) ? $dailyOnline[$dateStr]->total : 0;
            $pos = $dailyPos->has($dateStr) ? $dailyPos[$dateStr]->total : 0;
            
            $dates->push([
                'date' => $dateStr,
                'online' => $online,
                'pos' => $pos,
                'total' => $online + $pos
            ]);
            $currentDate->addDay();
        }

        return view('admin.reports.index', compact(
            'period', 'startDate', 'endDate', 
            'totalOnline', 'totalPos', 'totalCombined', 'dates'
        ));
    }

    public function exportPdf(Request $request)
    {
        // Assuming barryvdh/laravel-dompdf is installed and aliased to PDF
        $period = $request->get('period', 'today');
        
        $startDate = Carbon::today();
        $endDate = Carbon::today()->endOfDay();

        if ($period === 'week') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } elseif ($period === 'month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } elseif ($period === 'custom' && $request->filled(['start_date', 'end_date'])) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
        }

        // Daily Recap (Online)
        $dailyOnline = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->get()->keyBy('date');

        // Daily Recap (POS)
        $dailyPos = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(grand_total) as total'))
            ->groupBy('date')
            ->get()->keyBy('date');

        // Merge daily recap
        $dates = collect();
        $currentDate = $startDate->copy();
        $totalOnline = 0;
        $totalPos = 0;
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $online = $dailyOnline->has($dateStr) ? $dailyOnline[$dateStr]->total : 0;
            $pos = $dailyPos->has($dateStr) ? $dailyPos[$dateStr]->total : 0;
            
            $totalOnline += $online;
            $totalPos += $pos;

            $dates->push([
                'date' => $dateStr,
                'online' => $online,
                'pos' => $pos,
                'total' => $online + $pos
            ]);
            $currentDate->addDay();
        }

        $pdf = \PDF::loadView('admin.reports.pdf', compact('dates', 'period', 'startDate', 'endDate', 'totalOnline', 'totalPos'));
        return $pdf->download('laporan-penjualan.pdf');
    }

    public function exportExcel(Request $request)
    {
        // Assuming maatwebsite/excel is installed
        // We can just use an Export class if we had one, but we can also use fromView
        return \Excel::download(new \App\Exports\ReportExport($request), 'laporan-penjualan.xlsx');
    }
}
