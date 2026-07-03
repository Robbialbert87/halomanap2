<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('ticket_id')->nullable();
            $table->unsignedBigInteger('workflow_history_id')->nullable();
            $table->unsignedBigInteger('recipient_user_id')->nullable();
            $table->string('nomor_wa');
            $table->enum('jenis', [
                'disposisi_baru',
                'eskalasi',
                'pengaduan_selesai',
                'reminder_sla',
                'monitoring_direktur',
                'pengaduan_ditutup',
            ]);
            $table->text('isi_pesan');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->string('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('set null');
            $table->foreign('workflow_history_id')->references('id')->on('workflow_histories')->onDelete('set null');
            $table->foreign('recipient_user_id')->references('id')->on('users')->onDelete('set null');

            $table->index('status');
            $table->index('jenis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
