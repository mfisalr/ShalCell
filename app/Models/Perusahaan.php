<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perusahaan extends Model
{
    use HasFactory;

    protected $table = 'perusahaans';

    protected $fillable = [
        'nama_perusahaan',
        'credit_limit',
        'sisa_limit',
        'term_of_payment',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'sisa_limit' => 'decimal:2',
        'term_of_payment' => 'integer',
    ];

    public function tarifKhusus(): HasMany
    {
        return $this->hasMany(TarifKhusus::class);
    }

    public function hasSufficientLimit(float|int|string $amount): bool
    {
        if (! is_numeric($amount) || $amount < 0) {
            return false;
        }

        return (float) $this->sisa_limit >= (float) $amount;
    }
}
