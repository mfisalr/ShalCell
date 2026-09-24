<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE customers ADD saldo_awal DECIMAL(15, 2) NOT NULL DEFAULT 0 AFTER nomor');
        DB::statement('ALTER TABLE customers ADD saldo_tersisa DECIMAL(15, 2) NOT NULL DEFAULT 0 AFTER saldo_awal');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE customers DROP COLUMN saldo_awal');
        DB::statement('ALTER TABLE customers DROP COLUMN saldo_tersisa');
    }
};
