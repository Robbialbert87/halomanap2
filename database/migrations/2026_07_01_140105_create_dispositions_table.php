<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispositions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('head_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('priority', ['rendah', 'normal', 'tinggi', 'sangat_tinggi'])->default('normal');
            $table->text('instruction');
            $table->date('deadline');
            $table->enum('status', [
                'menunggu_respon',
                'diterima',
                'ditugaskan',
                'dalam_penanganan',
                'menunggu_review',
                'revisi',
                'menunggu_verifikasi',
                'selesai',
            ])->default('menunggu_respon');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispositions');
    }
};
