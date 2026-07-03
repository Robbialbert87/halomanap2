<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disposition_activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('disposition_id');
            $table->foreign('disposition_id')->references('id')->on('dispositions')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('activity'); // e.g. "Disposisi Dibuat", "Kepala Unit Menerima"
            $table->text('description')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disposition_activities');
    }
};
