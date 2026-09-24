<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_invoice')->unique();
            $table->foreignId('perusahaan_id')->constrained('perusahaans')->cascadeOnDelete();
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->unsignedInteger('jumlah_order');
            $table->decimal('total_harga', 15, 2);
            $table->string('status')->default('issued');
            $table->timestamps();

            $table->unique(['perusahaan_id', 'periode_mulai', 'periode_selesai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
