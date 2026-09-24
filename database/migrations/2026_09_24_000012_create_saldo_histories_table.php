<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saldo_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saldo_id')->constrained('saldos')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('tipe');
            $table->decimal('nominal', 15, 2);
            $table->decimal('saldo_sebelum', 15, 2);
            $table->decimal('saldo_sesudah', 15, 2);
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saldo_histories');
    }
};
