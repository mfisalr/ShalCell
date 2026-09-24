<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaldoHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'saldo_id',
        'customer_id',
        'tipe',
        'nominal',
        'saldo_sebelum',
        'saldo_sesudah',
        'keterangan',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'saldo_sebelum' => 'decimal:2',
        'saldo_sesudah' => 'decimal:2',
    ];

    public function saldo(): BelongsTo
    {
        return $this->belongsTo(Saldo::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
