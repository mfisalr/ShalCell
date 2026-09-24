<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarif_khusus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('perusahaans')->cascadeOnDelete();
            $table->foreignId('rute_id')->constrained('rute_pengirimans')->cascadeOnDelete();
            $table->decimal('harga_diskon', 15, 2);
            $table->timestamps();

            $table->unique(['perusahaan_id', 'rute_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarif_khusus');
    }
};
