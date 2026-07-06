<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->enum('type', ['Pengaduan', 'Survei', 'Apresiasi', 'Informasi']);
            $table->foreignId('category_id')->nullable()->constrained('report_categories');
            $table->foreignId('room_id')->nullable()->constrained('rooms');
            $table->boolean('is_anonymous')->default(false);
            $table->string('reporter_name')->nullable();
            $table->string('reporter_phone')->nullable();
            $table->string('title');
            $table->text('description');
            $table->string('attachment_path')->nullable();
            $table->string('status')->default('NEW');
            $table->foreignId('sla_id')->nullable()->constrained('slas');
            $table->boolean('sla_breached')->default(false);
            $table->tinyInteger('rating')->nullable();
            $table->text('review')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
