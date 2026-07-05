<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── JABATANS ─────────────────────────────────────────────────
        Schema::table('jabatans', function (Blueprint $table) {
            if (Schema::hasColumn('jabatans', 'parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            }
        });

        Schema::table('jabatans', function (Blueprint $table) {
            if (Schema::hasColumn('jabatans', 'level')) {
                $table->dropColumn('level');
            }
            if (Schema::hasColumn('jabatans', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
            if (!Schema::hasColumn('jabatans', 'kategori_jabatan')) {
                $table->enum('kategori_jabatan', [
                    'Direktur', 'Kabid', 'Kabag', 'Kasi', 'Kasubbag', 'Kepala Unit', 'Petugas'
                ])->default('Petugas')->after('nama');
            }
        });

        // ── UNITS ────────────────────────────────────────────────────
        Schema::table('units', function (Blueprint $table) {
            if (Schema::hasColumn('units', 'parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            }
        });

        Schema::table('units', function (Blueprint $table) {
            if (Schema::hasColumn('units', 'entry_jabatan_id')) {
                $table->dropForeign(['entry_jabatan_id']);
                $table->dropColumn('entry_jabatan_id');
            }
        });

        Schema::table('units', function (Blueprint $table) {
            if (Schema::hasColumn('units', 'is_public')) {
                $table->dropColumn('is_public');
            }
            if (Schema::hasColumn('units', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
            if (Schema::hasColumn('units', 'head_user_id')) {
                $table->dropForeign(['head_user_id']);
                $table->dropColumn('head_user_id');
            }
        });

        // ── WORKFLOW HISTORIES ───────────────────────────────────────
        Schema::table('workflow_histories', function (Blueprint $table) {
            if (Schema::hasColumn('workflow_histories', 'workflow_level')) {
                $table->dropColumn('workflow_level');
            }
        });
    }

    public function down(): void
    {
        // ── JABATANS ─────────────────────────────────────────────────
        Schema::table('jabatans', function (Blueprint $table) {
            if (Schema::hasColumn('jabatans', 'kategori_jabatan')) {
                $table->dropColumn('kategori_jabatan');
            }
            if (!Schema::hasColumn('jabatans', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('nama');
            }
            if (!Schema::hasColumn('jabatans', 'level')) {
                $table->tinyInteger('level')->default(4)->after('nama');
            }
            if (!Schema::hasColumn('jabatans', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('level');
                $table->foreign('parent_id')->references('id')->on('jabatans')->onDelete('set null');
            }
        });

        // ── UNITS ────────────────────────────────────────────────────
        Schema::table('units', function (Blueprint $table) {
            if (!Schema::hasColumn('units', 'head_user_id')) {
                $table->foreignId('head_user_id')->nullable()->constrained('users')->nullOnDelete()->after('status');
            }
            if (!Schema::hasColumn('units', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('jenis');
            }
            if (!Schema::hasColumn('units', 'is_public')) {
                $table->boolean('is_public')->default(false)->after('jenis');
            }
            if (!Schema::hasColumn('units', 'entry_jabatan_id')) {
                $table->foreignId('entry_jabatan_id')->nullable()->constrained('jabatans')->nullOnDelete()->after('jenis');
            }
            if (!Schema::hasColumn('units', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('jenis');
                $table->foreign('parent_id')->references('id')->on('units')->onDelete('set null');
            }
        });

        // ── WORKFLOW HISTORIES ───────────────────────────────────────
        Schema::table('workflow_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('workflow_histories', 'workflow_level')) {
                $table->integer('workflow_level')->default(1)->after('to_unit_id');
            }
        });
    }
};
