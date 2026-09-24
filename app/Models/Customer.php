<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'nomor', 'saldo_awal', 'saldo_tersisa', 'nominal', 'keterangan'];

    protected $casts = [
        'nominal' => 'decimal:2',
        'saldo_awal' => 'decimal:2',
        'saldo_tersisa' => 'decimal:2',
    ];
}
