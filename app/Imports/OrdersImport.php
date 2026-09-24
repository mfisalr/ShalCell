<?php

namespace App\Imports;

use App\Models\Order;
use App\Models\Perusahaan;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class OrdersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows, WithChunkReading
{
    use Importable, SkipsFailures;

    private int $importedCount = 0;

    public function model(array $row): Order
    {
        $this->importedCount++;

        return new Order([
            'kode_resi' => trim((string) $row['kode_resi']),
            'perusahaan_id' => Perusahaan::where('nama_perusahaan', trim((string) $row['name']))->value('id'),
            'alamat_tujuan' => trim((string) $row['address']),
            'berat_cbm' => $row['berat_cbm'],
            'status' => strtolower(trim((string) $row['status'])),
        ]);
    }

    public function rules(): array
    {
        return [
            'kode_resi' => [
                'required',
                'string',
                'max:100',
                'distinct',
                Rule::unique('orders', 'kode_resi'),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::exists('perusahaans', 'nama_perusahaan'),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:65535'],
            'phone' => ['nullable', 'string', 'max:50'],
            'berat_cbm' => ['required', 'numeric', 'gt:0'],
            'status' => [
                'required',
                'string',
                Rule::in(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            ],
        ];
    }

    public function importedCount(): int
    {
        return $this->importedCount;
    }

    public function customValidationMessages(): array
    {
        return [
            'kode_resi.unique' => 'Kode resi sudah terdaftar.',
            'kode_resi.distinct' => 'Kode resi tidak boleh duplikat dalam file.',
            'name.exists' => 'Nama perusahaan pada kolom name tidak ditemukan.',
            'berat_cbm.gt' => 'Berat CBM harus lebih besar dari 0.',
            'status.in' => 'Status harus pending, processing, shipped, delivered, atau cancelled.',
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public static function failureMessages(iterable $failures): array
    {
        $messages = [];

        /** @var Failure $failure */
        foreach ($failures as $failure) {
            $messages[] = sprintf(
                'Baris %d (%s): %s',
                $failure->row(),
                $failure->attribute(),
                implode(' ', $failure->errors())
            );
        }

        return $messages;
    }
}
