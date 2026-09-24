<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function consolidate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'perusahaan_id' => ['required', 'integer', 'exists:perusahaans,id'],
            'bulan' => ['required', 'date_format:Y-m'],
        ]);

        $periodeMulai = Carbon::createFromFormat('Y-m', $validated['bulan'])->startOfMonth();
        $periodeSelesai = $periodeMulai->copy()->endOfMonth();

        $orders = Order::query()
            ->where('perusahaan_id', $validated['perusahaan_id'])
            ->where('status', 'Delivered')
            ->whereBetween('created_at', [$periodeMulai, $periodeSelesai])
            ->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada order Delivered untuk perusahaan dan periode tersebut.',
            ], 422);
        }

        $groupedOrders = $orders->groupBy('perusahaan_id');

        $invoices = DB::transaction(function () use ($groupedOrders, $periodeMulai, $periodeSelesai): array {
            $createdInvoices = [];

            foreach ($groupedOrders as $perusahaanId => $companyOrders) {
                $totalHarga = round(
                    $companyOrders->sum(fn (Order $order): float => (float) $order->total_harga),
                    2
                );

                $invoice = Invoice::updateOrCreate(
                    [
                        'perusahaan_id' => $perusahaanId,
                        'periode_mulai' => $periodeMulai->toDateString(),
                        'periode_selesai' => $periodeSelesai->toDateString(),
                    ],
                    [
                        'nomor_invoice' => sprintf('INV-%d-%s', $perusahaanId, $periodeMulai->format('Ym')),
                        'jumlah_order' => $companyOrders->count(),
                        'total_harga' => $totalHarga,
                        'status' => 'issued',
                    ]
                );

                $createdInvoices[] = $invoice->fresh('perusahaan');
            }

            return $createdInvoices;
        });

        return response()->json([
            'message' => 'Consolidated invoice berhasil dibuat.',
            'data' => $invoices,
        ]);
    }
}
