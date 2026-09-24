<?php

namespace App\Exports;

use App\Models\SaldoHistory;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SaldoRecapExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    private Collection $histories;

    public function __construct(
        private readonly Carbon $periodeMulai,
        private readonly Carbon $periodeSelesai,
    ) {
    }

    public function collection(): Collection
    {
        return $this->histories = SaldoHistory::query()
            ->with('customer')
            ->whereBetween('created_at', [$this->periodeMulai, $this->periodeSelesai])
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $sheet->insertNewRowBefore(1, 6);
                $lastRow = $this->histories->count() + 7;
                $penambahan = (float) $this->histories->where('tipe', 'penambahan')->sum('nominal');
                $pengurangan = (float) $this->histories->where('tipe', 'pengurangan')->sum('nominal');

                $sheet->mergeCells('A1:G1');
                $sheet->mergeCells('A2:G2');
                $sheet->setCellValue('A1', 'SHALCELL - REKAP DATA SALDO');
                $sheet->setCellValue('A2', 'Periode: ' . $this->periodeMulai->format('d/m/Y') . ' - ' . $this->periodeSelesai->format('d/m/Y'));
                $sheet->setCellValue('A4', 'Total Penambahan');
                $sheet->setCellValue('B4', $penambahan);
                $sheet->setCellValue('D4', 'Total Pengurangan');
                $sheet->setCellValue('E4', $pengurangan);
                $sheet->setCellValue('A5', 'Jumlah Transaksi');
                $sheet->setCellValue('B5', $this->histories->count());

                $sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 15],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '7928CA']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('A2:G2')->applyFromArray([
                    'font' => ['italic' => true, 'color' => ['rgb' => '67748E']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('A4:E5')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '344767']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F3F4F6']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D9E2EC']]],
                ]);
                $sheet->getStyle('B4:B4')->getNumberFormat()->setFormatCode('"Rp" #,##0');
                $sheet->getStyle('E4:E4')->getNumberFormat()->setFormatCode('"Rp" #,##0');
                $sheet->getStyle("A7:G7")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '344767']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle("A7:G{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle("D8:F{$lastRow}")->getNumberFormat()->setFormatCode('"Rp" #,##0');
                $sheet->freezePane('A8');
                $sheet->setAutoFilter("A7:G{$lastRow}");

                foreach (['A' => 20, 'B' => 18, 'C' => 24, 'D' => 18, 'E' => 18, 'F' => 18, 'G' => 28] as $column => $width) {
                    $sheet->getColumnDimension($column)->setWidth($width);
                }
            },
        ];
    }
}
