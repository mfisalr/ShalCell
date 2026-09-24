<?php

namespace App\Http\Controllers;

use App\Exports\SaldoRecapExport;
use App\Models\Saldo;
use App\Models\SaldoHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class RecapController extends Controller
{
    public function index(Request $request): View
    {
        [$mode, $period, $periodeMulai, $periodeSelesai] = $this->period($request);
        $histories = $this->histories($periodeMulai, $periodeSelesai);
        $totalPenambahan = $this->totalByType($histories, 'penambahan');
        $totalPengurangan = $this->totalByType($histories, 'pengurangan');
        $saldo = Saldo::query()->firstOrCreate([], ['nominal' => 0]);

        return view('recaps.index', compact(
            'mode',
            'period',
            'periodeMulai',
            'periodeSelesai',
            'histories',
            'totalPenambahan',
            'totalPengurangan',
            'saldo'
        ));
    }

    public function exportExcel(Request $request)
    {
        [$mode, $period, $periodeMulai, $periodeSelesai] = $this->period($request);
        $filename = "rekap-saldo-{$mode}-{$period}.xlsx";

        return Excel::download(new SaldoRecapExport($periodeMulai, $periodeSelesai), $filename);
    }

    public function exportPdf(Request $request)
    {
        [$mode, $period, $periodeMulai, $periodeSelesai] = $this->period($request);
        $histories = $this->histories($periodeMulai, $periodeSelesai);
        $totalPenambahan = $this->totalByType($histories, 'penambahan');
        $totalPengurangan = $this->totalByType($histories, 'pengurangan');
        $saldo = Saldo::query()->firstOrCreate([], ['nominal' => 0]);
        $filename = "rekap-saldo-{$mode}-{$period}.pdf";

        return Pdf::loadView('recaps.pdf', compact(
            'mode',
            'period',
            'periodeMulai',
            'periodeSelesai',
            'histories',
            'totalPenambahan',
            'totalPengurangan',
            'saldo'
        ))->setPaper('a4', 'landscape')->download($filename);
    }

    private function histories(Carbon $periodeMulai, Carbon $periodeSelesai)
    {
        return SaldoHistory::query()
            ->with('customer')
            ->whereBetween('created_at', [$periodeMulai, $periodeSelesai])
            ->latest()
            ->get();
    }

    private function totalByType($histories, string $type): float
    {
        return (float) $histories
            ->where('tipe', $type)
            ->sum(fn (SaldoHistory $history): float => (float) $history->nominal);
    }

    private function period(Request $request): array
    {
        $mode = in_array($request->input('mode'), ['monthly', 'yearly'], true)
            ? $request->input('mode')
            : 'monthly';
        $period = trim((string) $request->input('period', ''));

        if ($mode === 'monthly' && preg_match('/^\d{4}-(?:0[1-9]|1[0-2])(?:-\d{2})?$/', $period)) {
            $periodeMulai = Carbon::createFromFormat('Y-m', substr($period, 0, 7))->startOfMonth();
            $period = $periodeMulai->format('Y-m');
        } elseif ($mode === 'yearly' && preg_match('/^\d{4}$/', $period)) {
            $periodeMulai = Carbon::createFromFormat('Y', $period)->startOfYear();
        } else {
            $period = $mode === 'monthly' ? now()->format('Y-m') : now()->format('Y');
            $periodeMulai = $mode === 'monthly'
                ? now()->startOfMonth()
                : now()->startOfYear();
        }

        $periodeSelesai = $mode === 'monthly'
            ? $periodeMulai->copy()->endOfMonth()
            : $periodeMulai->copy()->endOfYear();

        return [$mode, $period, $periodeMulai, $periodeSelesai];
    }
}
