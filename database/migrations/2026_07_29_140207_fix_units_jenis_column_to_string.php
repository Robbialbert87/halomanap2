<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE units MODIFY COLUMN jenis VARCHAR(100) NOT NULL DEFAULT "Lainnya"');
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE units MODIFY COLUMN jenis ENUM('Instalasi','Bidang','Bagian','Sub Bagian','Komite','Tim','Pelayanan','Penunjang','Lainnya') NOT NULL DEFAULT 'Lainnya'");
    }
};
