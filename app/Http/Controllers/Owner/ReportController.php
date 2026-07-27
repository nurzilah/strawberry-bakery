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

        $totalOnline = Order::where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        $totalPos = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('grand_total');

        $totalCombined = $totalOnline + $totalPos;

        $dailyOnline = Order::where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->get()->keyBy('date');

        $dailyPos = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(grand_total) as total'))
            ->groupBy('date')
            ->get()->keyBy('date');

        $dates = collect();
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $online = $dailyOnline->has($dateStr) ? $dailyOnline[$dateStr]->total : 0;
            $pos = $dailyPos->has($dateStr) ? $dailyPos[$dateStr]->total : 0;

            $dates->push([
                'date'   => $dateStr,
                'online' => $online,
                'pos'    => $pos,
                'total'  => $online + $pos
            ]);
            $currentDate->addDay();
        }

        return view('admin.reports.index', compact(
            'period', 'startDate', 'endDate',
            'totalOnline', 'totalPos', 'totalCombined', 'dates'
        ));
    }

    private function buildDates(Request $request): array
    {
        $period = $request->get('period', 'today');

        $startDate = Carbon::today();
        $endDate   = Carbon::today()->endOfDay();

        if ($period === 'week') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate   = Carbon::now()->endOfWeek();
        } elseif ($period === 'month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate   = Carbon::now()->endOfMonth();
        } elseif ($period === 'custom' && $request->filled(['start_date', 'end_date'])) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate   = Carbon::parse($request->end_date)->endOfDay();
        }

        $dailyOnline = Order::where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')->get()->keyBy('date');

        $dailyPos = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(grand_total) as total'))
            ->groupBy('date')->get()->keyBy('date');

        $dates      = collect();
        $totalOnline = 0;
        $totalPos    = 0;
        $current    = $startDate->copy();

        while ($current <= $endDate) {
            $dateStr = $current->format('Y-m-d');
            $online  = $dailyOnline->has($dateStr) ? $dailyOnline[$dateStr]->total : 0;
            $pos     = $dailyPos->has($dateStr)    ? $dailyPos[$dateStr]->total    : 0;

            $totalOnline += $online;
            $totalPos    += $pos;

            $dates->push([
                'date'   => $dateStr,
                'online' => $online,
                'pos'    => $pos,
                'total'  => $online + $pos,
            ]);
            $current->addDay();
        }

        return compact('dates', 'period', 'startDate', 'endDate', 'totalOnline', 'totalPos');
    }

    public function exportPdf(Request $request)
    {
        $data = $this->buildDates($request);

        $pdf = Pdf::loadView('admin.reports.pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-penjualan-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->buildDates($request);

        $rows = $data['dates']->map(fn($d) => [
            'Tanggal'           => $d['date'],
            'Pendapatan Online' => $d['online'],
            'Pendapatan POS'    => $d['pos'],
            'Total'             => $d['total'],
        ]);

        return (new FastExcel($rows))
            ->download('laporan-penjualan-' . now()->format('Y-m-d') . '.xlsx');
    }
}