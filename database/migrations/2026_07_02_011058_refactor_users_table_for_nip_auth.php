<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id')->nullable();
            $table->string('nip')->unique()->after('uuid')->nullable();
            $table->renameColumn('name', 'nama');
            $table->string('email')->nullable()->change();
            $table->string('phone_number')->nullable()->after('email');

            // replace is_active with status
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('jabatan');

            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'nip', 'phone_number', 'status', 'last_login_at', 'last_login_ip']);
            $table->dropSoftDeletes();
            $table->renameColumn('nama', 'name');
            $table->string('email')->nullable(false)->change();
            $table->boolean('is_active')->default(true)->after('jabatan');
        });
    }
};
