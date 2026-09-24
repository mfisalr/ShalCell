<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PodController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'foto_surat_jalan' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'nama_penerima' => ['required', 'string', 'max:255'],
            'koordinat_gps' => [
                'required',
                'string',
                'regex:/^-?\d{1,3}(?:\.\d+)?\s*,\s*-?\d{1,3}(?:\.\d+)?$/',
            ],
        ], [
            'foto_surat_jalan.image' => 'File surat jalan harus berupa gambar.',
            'foto_surat_jalan.mimes' => 'Foto harus berformat JPG, JPEG, PNG, atau WEBP.',
            'foto_surat_jalan.max' => 'Ukuran foto maksimal 5 MB.',
            'koordinat_gps.regex' => 'Koordinat GPS harus berformat latitude,longitude.',
        ]);

        $order = Order::findOrFail($validated['order_id']);
        $oldPhotoPath = $order->foto_surat_jalan;
        $photoPath = null;

        try {
            $photoPath = $request->file('foto_surat_jalan')->store('e-pod', 'public');

            DB::transaction(function () use ($order, $validated, $photoPath): void {
                $order->update([
                    'foto_surat_jalan' => $photoPath,
                    'nama_penerima' => $validated['nama_penerima'],
                    'koordinat_gps' => $validated['koordinat_gps'],
                    'status' => 'Delivered',
                ]);
            });

            if ($oldPhotoPath && $oldPhotoPath !== $photoPath) {
                Storage::disk('public')->delete($oldPhotoPath);
            }
        } catch (Throwable $exception) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            report($exception);

            return response()->json([
                'message' => 'Data e-POD gagal disimpan.',
            ], 500);
        }

        return response()->json([
            'message' => 'e-POD berhasil disimpan.',
            'data' => [
                'order_id' => $order->id,
                'status' => $order->status,
                'foto_surat_jalan' => $photoPath,
                'foto_url' => Storage::disk('public')->url($photoPath),
                'nama_penerima' => $order->nama_penerima,
                'koordinat_gps' => $order->koordinat_gps,
            ],
        ], 200);
    }
}
