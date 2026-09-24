<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_invoice',
        'perusahaan_id',
        'periode_mulai',
        'periode_selesai',
        'jumlah_order',
        'total_harga',
        'status',
    ];

    protected $casts = [
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
        'jumlah_order' => 'integer',
        'total_harga' => 'decimal:2',
    ];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }
}
