<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Saldo extends Model
{
    use HasFactory;

    protected $fillable = ['nominal'];

    protected $casts = [
        'nominal' => 'decimal:2',
    ];

    public function histories(): HasMany
    {
        return $this->hasMany(SaldoHistory::class);
    }
}
