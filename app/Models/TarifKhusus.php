<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TarifKhusus extends Model
{
    use HasFactory;

    protected $table = 'tarif_khusus';

    protected $fillable = [
        'perusahaan_id',
        'rute_id',
        'harga_diskon',
    ];

    protected $casts = [
        'harga_diskon' => 'decimal:2',
    ];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function rute(): BelongsTo
    {
        return $this->belongsTo(RutePengiriman::class, 'rute_id');
    }
}
