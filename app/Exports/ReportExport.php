<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;

class ReportExport implements FromView
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $period = $this->request->get('period', 'today');
        
        $startDate = Carbon::today();
        $endDate = Carbon::today()->endOfDay();

        if ($period === 'week') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } elseif ($period === 'month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } elseif ($period === 'custom' && $this->request->filled(['start_date', 'end_date'])) {
            $startDate = Carbon::parse($this->request->start_date)->startOfDay();
            $endDate = Carbon::parse($this->request->end_date)->endOfDay();
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

        return view('admin.reports.excel', compact('dates', 'period', 'startDate', 'endDate', 'totalOnline', 'totalPos'));
    }
}
