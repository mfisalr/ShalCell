<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    use HasFactory;

    public function province(){
        return $this->belongTo(Province::class);
    }

    public function disctricts(){
        return $this->hasMany(District::class);
    }
}
