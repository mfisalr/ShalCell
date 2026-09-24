<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perusahaans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan');
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->decimal('sisa_limit', 15, 2)->default(0);
            $table->unsignedInteger('term_of_payment');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perusahaans');
    }
};
