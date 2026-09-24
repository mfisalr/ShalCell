<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE customers CHANGE name nama VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE customers CHANGE email nomor VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE customers CHANGE address pulsa TEXT NOT NULL');
        DB::statement('ALTER TABLE customers CHANGE phone kartu TEXT NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE customers CHANGE nama name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE customers CHANGE nomor email VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE customers CHANGE pulsa address TEXT NOT NULL');
        DB::statement('ALTER TABLE customers CHANGE kartu phone TEXT NOT NULL');
    }
};
