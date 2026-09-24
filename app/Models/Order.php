<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_resi',
        'perusahaan_id',
        'alamat_tujuan',
        'berat_cbm',
        'total_harga',
        'status',
        'foto_surat_jalan',
        'nama_penerima',
        'koordinat_gps',
    ];

    protected $casts = [
        'berat_cbm' => 'decimal:3',
        'total_harga' => 'decimal:2',
    ];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }
}
