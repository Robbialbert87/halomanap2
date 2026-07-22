<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            // Add new columns
            $table->uuid('uuid')->nullable()->unique()->after('id');
            $table->string('kode')->nullable()->unique()->after('uuid');

            // Rename name to nama (add new column first for safety)
            $table->string('nama')->nullable()->after('kode');

            $table->enum('jenis', [
                'Instalasi', 'Bidang', 'Bagian', 'Sub Bagian',
                'Komite', 'Tim', 'Pelayanan', 'Penunjang', 'Lainnya',
            ])->default('Lainnya')->after('nama');

            $table->unsignedBigInteger('parent_id')->nullable()->after('jenis');
            $table->text('keterangan')->nullable()->after('parent_id');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('keterangan');
            $table->softDeletes();
        });

        // Copy existing name → nama
        DB::statement('UPDATE units SET nama = name WHERE nama IS NULL');

        // Generate UUID and kode for existing records
        foreach (DB::table('units')->get() as $unit) {
            DB::table('units')->where('id', $unit->id)->update([
                'uuid' => (string) Str::uuid(),
                'kode' => 'UNIT_'.str_pad($unit->id, 3, '0', STR_PAD_LEFT),
            ]);
        }

        // Now drop the old name column
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        // Make nama not nullable now
        Schema::table('units', function (Blueprint $table) {
            $table->string('nama')->nullable(false)->change();
            $table->string('kode')->nullable(false)->change();
            $table->uuid('uuid')->nullable(false)->change();
        });

        // Add self-referencing FK
        Schema::table('units', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('units')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropSoftDeletes();
            $table->dropColumn(['uuid', 'kode', 'jenis', 'parent_id', 'keterangan', 'status']);
            $table->string('name')->default('');
        });

        DB::statement('UPDATE units SET name = nama');
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('nama');
        });
    }
};
