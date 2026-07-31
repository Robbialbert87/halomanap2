<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DELETE n1 FROM notification_logs n1, notification_logs n2
            WHERE n1.id > n2.id
            AND n1.workflow_history_id = n2.workflow_history_id
            AND n1.recipient_user_id = n2.recipient_user_id
            AND n1.jenis = n2.jenis');

        try {
            Schema::table('notification_logs', function (Blueprint $table) {
                $table->unique(['workflow_history_id', 'recipient_user_id', 'jenis'], 'uq_notif_unique_send');
            });
        } catch (Exception $e) {
            // index already exists or other issue, ignore
        }
    }

    public function down(): void
    {
        Schema::table('notification_logs', function (Blueprint $table) {
            $table->dropUnique('uq_notif_unique_send');
        });
    }
};
