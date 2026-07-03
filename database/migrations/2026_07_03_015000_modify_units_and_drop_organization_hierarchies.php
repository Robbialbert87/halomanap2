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
        Schema::table('units', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->after('jenis');
            $table->foreignId('entry_jabatan_id')->nullable()->constrained('jabatans')->nullOnDelete()->after('parent_id');
        });

        Schema::dropIfExists('organization_hierarchies');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('organization_hierarchies', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('jabatan_id')->constrained('jabatans')->cascadeOnDelete();
            $table->foreignId('parent_jabatan_id')->nullable()->constrained('jabatans')->nullOnDelete();
            $table->integer('urutan_level')->default(1);
            $table->integer('workflow_level')->default(1);
            $table->boolean('is_workflow_start')->default(false);
            $table->boolean('is_workflow_end')->default(false);
            $table->boolean('can_escalate')->default(true);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropForeign(['entry_jabatan_id']);
            $table->dropColumn(['is_public', 'entry_jabatan_id']);
        });
    }
};
