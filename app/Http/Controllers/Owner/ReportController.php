<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->buildReportData($request);
        return view('admin.reports.index', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->buildReportData($request);

        $pdf = Pdf::loadView('admin.reports.pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-penjualan-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->buildReportData($request);

        $rows = $data['dates']->map(fn($d) => [
            'Tanggal'           => $d['date'],
            'Pendapatan Online' => $d['online'],
            'Pendapatan POS'    => $d['pos'],
            'Total'             => $d['total'],
        ]);

        return (new FastExcel($rows))
            ->download('laporan-penjualan-' . now()->format('Y-m-d') . '.xlsx');
    }

    private function buildReportData(Request $request): array
    {
        $now = Carbon::now();
        $today = Carbon::today();

        // Cari rentang tahun yang tersedia dari database
        $earliestOrderYear = Order::min(DB::raw('YEAR(created_at)')) ?? $now->year;
        $earliestPosYear = Transaction::min(DB::raw('YEAR(created_at)')) ?? $now->year;
        $minYear = min((int)$earliestOrderYear, (int)$earliestPosYear, $now->year - 1);
        $availableYears = range($now->year, $minYear);

        // Parameter input
        $period = $request->get('period', 'month');
        $selectedYear = (int) $request->get('year', $now->year);
        $selectedMonth = $request->get('month', (string) $now->month);

        if ($period === 'custom' && $request->filled(['start_date', 'end_date'])) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
        } elseif ($period === 'today') {
            $startDate = $today->copy()->startOfDay();
            $endDate = $today->copy()->endOfDay();
        } elseif ($period === 'week') {
            $startDate = $now->copy()->startOfWeek()->startOfDay();
            $endDate = $now->copy()->endOfWeek()->endOfDay();
        } elseif ($period === 'year' || $selectedMonth === 'all' || $selectedMonth === '0') {
            $startDate = Carbon::createFromDate($selectedYear, 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate($selectedYear, 12, 31)->endOfYear();
            $period = 'year';
            $selectedMonth = 'all';
        } else {
            // Default: month
            $m = (int) $selectedMonth;
            if ($m < 1 || $m > 12) {
                $m = $now->month;
                $selectedMonth = (string) $m;
            }
            $startDate = Carbon::createFromDate($selectedYear, $m, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($selectedYear, $m, 1)->endOfMonth();
            $period = 'month';
        }

        // Helper filter order online yang sah (konsisten dengan AdminDashboardController)
        $validOrders = function ($q) {
            $q->whereNotIn('status', ['cancelled'])
              ->where(function ($sub) {
                  $sub->whereIn('payment_status', ['paid', 'success', 'settlement'])
                      ->orWhereIn('status', ['completed', 'processing', 'waiting_verification']);
              });
        };

        // Query Total
        $totalOnline = (float) Order::whereBetween('created_at', [$startDate, $endDate])
            ->where($validOrders)
            ->sum('total_amount');

        $totalPos = (float) Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('grand_total');

        $totalCombined = $totalOnline + $totalPos;

        // Query Harian
        $dailyOnline = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where($validOrders)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->get()->keyBy('date');

        $dailyPos = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(grand_total) as total'))
            ->groupBy('date')
            ->get()->keyBy('date');

        // Batasi rentang tanggal maksimal hingga hari ini (Carbon::today()) jika periode berada di bulan/tahun berjalan
        $maxDate = $today->copy()->endOfDay();
        $loopEndDate = $endDate->gt($maxDate) ? $maxDate : $endDate->copy();

        $dates = collect();
        if ($startDate->lte($loopEndDate)) {
            $currentDate = $startDate->copy();
            while ($currentDate <= $loopEndDate) {
                $dateStr = $currentDate->format('Y-m-d');
                $online = $dailyOnline->has($dateStr) ? (float) $dailyOnline[$dateStr]->total : 0.0;
                $pos = $dailyPos->has($dateStr) ? (float) $dailyPos[$dateStr]->total : 0.0;

                $dates->push([
                    'date'   => $dateStr,
                    'online' => $online,
                    'pos'    => $pos,
                    'total'  => $online + $pos
                ]);
                $currentDate->addDay();
            }
        }

        return compact(
            'period', 'selectedYear', 'selectedMonth', 'availableYears',
            'startDate', 'endDate',
            'totalOnline', 'totalPos', 'totalCombined', 'dates'
        );
    }
}
