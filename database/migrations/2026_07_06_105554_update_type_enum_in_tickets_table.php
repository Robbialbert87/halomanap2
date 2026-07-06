<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE tickets MODIFY COLUMN type VARCHAR(50) NOT NULL DEFAULT 'Pengaduan'");
        DB::statement("UPDATE tickets SET type = 'Survei' WHERE type = 'Saran'");
        DB::statement("ALTER TABLE tickets MODIFY COLUMN type ENUM('Pengaduan','Survei','Apresiasi','Informasi') NOT NULL DEFAULT 'Pengaduan'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE tickets MODIFY COLUMN type VARCHAR(50) NOT NULL DEFAULT 'Pengaduan'");
        DB::statement("UPDATE tickets SET type = 'Saran' WHERE type = 'Survei'");
        DB::statement("ALTER TABLE tickets MODIFY COLUMN type ENUM('Pengaduan','Saran','Apresiasi','Informasi') NOT NULL DEFAULT 'Pengaduan'");
    }
};
