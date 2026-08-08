<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom timestamp yang dihasilkan sistem (created_at/updated_at/dll)
     * tersimpan dalam UTC. Setelah timezone aplikasi diganti ke WIB,
     * data lama digeser +7 jam agar tampil sesuai WIB.
     *
     * Kolom input manual user (mis. dispositions.deadline,
     * ticket_dispositions.target_date) TIDAK digeser.
     */
    protected array $shifts = [
        'tickets'                 => ['created_at', 'updated_at', 'notification_seen_at'],
        'ticket_histories'        => ['created_at', 'updated_at'],
        'ticket_comments'         => ['created_at', 'updated_at'],
        'ticket_attachments'      => ['created_at', 'updated_at'],
        'ticket_responses'        => ['created_at', 'updated_at'],
        'ticket_dispositions'     => ['created_at', 'updated_at'],
        'workflow_histories'      => ['created_at', 'updated_at', 'due_at', 'completed_at'],
        'dispositions'            => ['created_at', 'updated_at', 'deleted_at', 'accepted_at', 'completed_at', 'verified_at'],
        'disposition_activities'  => ['created_at', 'updated_at'],
        'app_notifications'       => ['created_at', 'updated_at', 'read_at'],
        'notification_logs'       => ['created_at', 'updated_at', 'sent_at', 'read_at'],
        'audit_trails'            => ['created_at'],
        'appreciations'           => ['created_at', 'updated_at'],
        'rooms'                   => ['created_at', 'updated_at'],
        'units'                   => ['created_at', 'updated_at', 'deleted_at'],
        'report_categories'       => ['created_at', 'updated_at'],
        'slas'                    => ['created_at', 'updated_at'],
        'unit_types'              => ['created_at', 'updated_at'],
        'jabatans'                => ['created_at', 'updated_at', 'deleted_at'],
        'settings'                => ['created_at', 'updated_at'],
        'roles'                   => ['created_at', 'updated_at', 'deleted_at'],
        'permissions'             => ['created_at', 'updated_at'],
        'users'                   => ['created_at', 'updated_at', 'deleted_at', 'email_verified_at', 'last_login_at'],
        'password_reset_tokens'   => ['created_at'],
        'jobs'                    => ['created_at', 'available_at', 'reserved_at', 'failed_at'],
        'job_batches'             => ['created_at', 'cancelled_at', 'finished_at'],
        'failed_jobs'             => ['failed_at'],
    ];

    public function up(): void
    {
        $this->shift('+7 HOUR');
    }

    public function down(): void
    {
        $this->shift('-7 HOUR');
    }

    protected function shift(string $interval): void
    {
        foreach ($this->shifts as $table => $columns) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                if (!Schema::hasColumn($table, $column)) {
                    continue;
                }

                DB::statement(
                    "UPDATE `{$table}` SET `{$column}` = DATE_ADD(`{$column}`, INTERVAL {$interval}) WHERE `{$column}` IS NOT NULL"
                );
            }
        }
    }
};
