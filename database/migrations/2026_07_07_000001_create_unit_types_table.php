<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $defaults = ['Instalasi', 'Bidang', 'Bagian', 'Sub Bagian', 'Komite', 'Tim', 'Pelayanan', 'Penunjang', 'Lainnya'];
        $colors = ['#3b82f6', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#f97316', '#ef4444', '#ec4899', '#6b7280'];

        foreach ($defaults as $i => $name) {
            DB::table('unit_types')->insert([
                'name' => $name,
                'color' => $colors[$i] ?? '#6b7280',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_types');
    }
};
