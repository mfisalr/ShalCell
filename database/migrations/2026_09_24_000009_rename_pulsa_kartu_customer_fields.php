<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE customers CHANGE pulsa nominal DECIMAL(15, 2) NOT NULL');
        DB::statement('ALTER TABLE customers CHANGE kartu keterangan VARCHAR(100) NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE customers CHANGE nominal pulsa TEXT NOT NULL');
        DB::statement('ALTER TABLE customers CHANGE keterangan kartu TEXT NOT NULL');
    }
};
