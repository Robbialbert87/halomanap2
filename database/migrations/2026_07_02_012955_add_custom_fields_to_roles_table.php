<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id')->nullable();
            $table->string('kode')->unique()->after('uuid')->nullable();
            $table->text('deskripsi')->nullable()->after('name');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('guard_name');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'kode', 'deskripsi', 'status']);
            $table->dropSoftDeletes();
        });
    }
};
