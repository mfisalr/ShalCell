<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('foto_surat_jalan')->nullable()->after('alamat_tujuan');
            $table->string('nama_penerima')->nullable()->after('foto_surat_jalan');
            $table->string('koordinat_gps')->nullable()->after('nama_penerima');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'foto_surat_jalan',
                'nama_penerima',
                'koordinat_gps',
            ]);
        });
    }
};
