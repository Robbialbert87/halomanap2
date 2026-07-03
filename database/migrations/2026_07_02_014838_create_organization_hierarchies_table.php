<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_hierarchies', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
            $table->foreignId('jabatan_id')->constrained('jabatans')->cascadeOnDelete();

            // Atasan langsung dalam satu unit
            $table->unsignedBigInteger('parent_jabatan_id')->nullable();
            $table->foreign('parent_jabatan_id')->references('id')->on('jabatans')->onDelete('set null');

            $table->unsignedTinyInteger('urutan_level');    // Urutan posisi dalam hierarki unit ini
            $table->unsignedTinyInteger('workflow_level');  // Level eskalasi (1 = paling bawah)

            $table->boolean('is_workflow_start')->default(false); // Entry point workflow (misal: Kepala Unit)
            $table->boolean('is_workflow_end')->default(false);   // Puncak eskalasi (misal: Direktur)
            $table->boolean('can_escalate')->default(true);       // Boleh meneruskan disposisi ke atas

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            // Unique: satu jabatan hanya ada satu kali per unit
            $table->unique(['unit_id', 'jabatan_id'], 'unique_jabatan_per_unit');
            // Unique: workflow level tidak boleh sama dalam satu unit
            $table->unique(['unit_id', 'workflow_level'], 'unique_workflow_level_per_unit');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_hierarchies');
    }
};
