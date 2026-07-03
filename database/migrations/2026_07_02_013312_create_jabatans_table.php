<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jabatans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->tinyInteger('level'); // 1: Direktur, 2: Kabid/Kabag, 3: Kasi/Kasubbag, 4: Kepala Unit
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            // Self-referencing foreign key
            $table->foreign('parent_id')->references('id')->on('jabatans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jabatans');
    }
};
