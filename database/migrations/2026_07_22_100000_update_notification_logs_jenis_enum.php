<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE notification_logs MODIFY COLUMN jenis ENUM(
            'disposisi_baru',
            'eskalasi',
            'pengaduan_selesai',
            'reminder_sla',
            'monitoring_direktur',
            'pengaduan_ditutup',
            'dalam_penanganan',
            'admin_verifikasi'
        ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE notification_logs MODIFY COLUMN jenis ENUM(
            'disposisi_baru',
            'eskalasi',
            'pengaduan_selesai',
            'reminder_sla',
            'monitoring_direktur',
            'pengaduan_ditutup'
        ) NOT NULL");
    }
};
