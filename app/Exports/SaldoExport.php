<?php

namespace App\Exports;

use App\Models\SaldoHistory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SaldoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection(): Collection
    {
        return SaldoHistory::query()
            ->with('customer')
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return ['Tanggal', 'Tipe', 'Customer', 'Nominal', 'Saldo Sebelum', 'Saldo Sesudah', 'Keterangan'];
    }

    public function map($history): array
    {
        return [
            $history->created_at->format('d/m/Y H:i'),
            ucfirst($history->tipe),
            $history->customer?->nama ?? '-',
            $history->nominal,
            $history->saldo_sebelum,
            $history->saldo_sesudah,
            ucwords($history->keterangan),
        ];
    }
}
