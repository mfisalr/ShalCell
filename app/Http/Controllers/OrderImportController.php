<?php

namespace App\Http\Controllers;

use App\Imports\OrdersImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class OrderImportController extends Controller
{
    public function create(): View
    {
        return view('orders.import');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx', 'max:10240'],
        ], [
            'file.required' => 'Pilih file Excel terlebih dahulu.',
            'file.mimes' => 'File harus berformat .xlsx.',
            'file.max' => 'Ukuran file maksimal 10 MB.',
        ]);

        $import = new OrdersImport();

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Throwable $exception) {
            Log::error('Bulk order import failed.', [
                'message' => $exception->getMessage(),
            ]);

            return back()->withInput()->withErrors([
                'file' => 'File gagal diproses. Pastikan heading dan format datanya sesuai template.',
            ]);
        }

        $failureMessages = OrdersImport::failureMessages($import->failures());
        return redirect()
            ->route('orders.import.create')
            ->with('success', "Import selesai. {$import->importedCount()} order berhasil diproses.")
            ->with('import_failures', $failureMessages);
    }
}
