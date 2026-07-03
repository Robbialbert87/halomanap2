<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gelar_depan')->nullable()->after('nama');
            $table->string('gelar_belakang')->nullable()->after('gelar_depan');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('gelar_belakang');
            
            // Note: jabatan is currently a string. We'll rename it, add a new foreign key.
            $table->renameColumn('jabatan', 'old_jabatan');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('jabatan_id')->nullable()->after('unit_id');
            $table->foreign('jabatan_id')->references('id')->on('jabatans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['jabatan_id']);
            $table->dropColumn(['jabatan_id', 'gelar_depan', 'gelar_belakang', 'jenis_kelamin']);
            $table->renameColumn('old_jabatan', 'jabatan');
        });
    }
};
