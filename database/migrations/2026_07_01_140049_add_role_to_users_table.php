<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('petugas')->after('email');
            // role: super_admin | admin_pengaduan | kepala_ruangan | kepala_seksi | kabid | direktur | petugas
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete()->after('role');
            $table->string('jabatan')->nullable()->after('unit_id');
            $table->boolean('is_active')->default(true)->after('jabatan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'unit_id', 'jabatan', 'is_active']);
        });
    }
};
