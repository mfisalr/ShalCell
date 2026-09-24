<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RutePengiriman extends Model
{
    use HasFactory;

    protected $table = 'rute_pengirimans';

    protected $fillable = [
        'asal',
        'tujuan',
        'base_price',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
    ];

    public function tarifKhusus(): HasMany
    {
        return $this->hasMany(TarifKhusus::class, 'rute_id');
    }
}
