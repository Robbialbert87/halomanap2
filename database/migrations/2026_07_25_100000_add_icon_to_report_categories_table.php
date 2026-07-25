<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('report_categories', function (Blueprint $table) {
            $table->string('icon', 50)->nullable()->after('name');
        });

        DB::table('report_categories')->where('name', 'Pelayanan Dokter')->update(['icon' => 'fa-user-doctor']);
        DB::table('report_categories')->where('name', 'Pelayanan Perawat')->update(['icon' => 'fa-user-nurse']);
        DB::table('report_categories')->where('name', 'Pelayanan Administrasi')->update(['icon' => 'fa-file-invoice']);
        DB::table('report_categories')->where('name', 'Fasilitas')->update(['icon' => 'fa-building']);
        DB::table('report_categories')->where('name', 'Kebersihan')->update(['icon' => 'fa-broom']);
        DB::table('report_categories')->where('name', 'Keamanan')->update(['icon' => 'fa-shield-halved']);
        DB::table('report_categories')->where('name', 'Informasi & Komunikasi')->update(['icon' => 'fa-circle-info']);
        DB::table('report_categories')->where('name', 'Lainnya')->update(['icon' => 'fa-tag']);
    }

    public function down(): void
    {
        Schema::table('report_categories', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};
