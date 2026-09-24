<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection(): Collection
    {
        return Customer::query()
            ->orderBy('nama')
            ->get();
    }

    public function headings(): array
    {
        return ['Nama', 'Nomor', 'Nominal', 'Keterangan'];
    }

    public function map($customer): array
    {
        return [
            $customer->nama,
            $customer->nomor,
            $customer->nominal,
            $customer->keterangan,
        ];
    }
}
