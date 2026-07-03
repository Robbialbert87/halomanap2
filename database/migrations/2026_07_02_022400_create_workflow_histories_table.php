<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->unsignedBigInteger('to_user_id')->nullable();
            $table->unsignedBigInteger('from_jabatan_id')->nullable();
            $table->unsignedBigInteger('to_jabatan_id')->nullable();
            $table->unsignedBigInteger('from_unit_id')->nullable();
            $table->unsignedBigInteger('to_unit_id')->nullable();
            $table->integer('workflow_level')->default(1);
            $table->enum('action', [
                'disposisi',
                'eskalasi',
                'tangani_sendiri',
                'selesai',
                'ditolak',
                'verifikasi',
                'tutup',
            ])->default('disposisi');
            $table->text('komentar')->nullable();
            $table->string('lampiran')->nullable();
            $table->enum('status', [
                'baru',
                'didisposisikan',
                'menunggu_respon',
                'dalam_penanganan',
                'eskalasi',
                'menunggu_verifikasi',
                'selesai',
                'ditutup',
            ])->default('baru');
            $table->timestamp('due_at')->nullable(); // SLA deadline
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('to_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('from_jabatan_id')->references('id')->on('jabatans')->onDelete('set null');
            $table->foreign('to_jabatan_id')->references('id')->on('jabatans')->onDelete('set null');
            $table->foreign('from_unit_id')->references('id')->on('units')->onDelete('set null');
            $table->foreign('to_unit_id')->references('id')->on('units')->onDelete('set null');

            $table->index('ticket_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_histories');
    }
};
