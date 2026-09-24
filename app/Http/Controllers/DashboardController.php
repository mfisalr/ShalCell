<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Saldo;
use App\Models\SaldoHistory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $customers = Customer::query()->orderBy('nama')->get();
        $saldo = Saldo::query()->firstOrCreate([], ['nominal' => 0]);
        $histories = $saldo->histories()->with('customer')->latest()->get();
        $totalPenambahan = (float) $histories->where('tipe', 'penambahan')->sum('nominal');
        $totalPengurangan = (float) $histories->where('tipe', 'pengurangan')->sum('nominal');
        $totalTransaksi = $histories->count();
        $aktivitasMaksimal = max($totalPenambahan, $totalPengurangan, $totalTransaksi, $customers->count(), 1);
        $chartLabels = collect(range(5, 0))->map(fn (int $months): string => now()->subMonths($months)->format('M Y'))->values();
        $chartPenambahan = $chartLabels->map(function (string $label) use ($histories): float {
            return (float) $histories
                ->filter(fn (SaldoHistory $history): bool => $history->tipe === 'penambahan' && $history->created_at->format('M Y') === $label)
                ->sum('nominal');
        })->values();
        $chartPengurangan = $chartLabels->map(function (string $label) use ($histories): float {
            return (float) $histories
                ->filter(fn (SaldoHistory $history): bool => $history->tipe === 'pengurangan' && $history->created_at->format('M Y') === $label)
                ->sum('nominal');
        })->values();

        return view('dashboard.index', compact(
            'customers',
            'saldo',
            'histories',
            'totalPenambahan',
            'totalPengurangan',
            'totalTransaksi',
            'aktivitasMaksimal',
            'chartLabels',
            'chartPenambahan',
            'chartPengurangan'
        ));
    }
}
