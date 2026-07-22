<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old unique indexes (may not exist if partial migration ran)
        foreach (['users_nip_unique', 'users_email_unique', 'users_uuid_unique'] as $index) {
            try {
                DB::statement("ALTER TABLE users DROP INDEX $index");
            } catch (Exception) {
                // index might not exist
            }
        }

        // Drop generated columns from a previous failed attempt
        foreach (['nip_unique', 'email_unique', 'uuid_unique'] as $col) {
            try {
                DB::statement("ALTER TABLE users DROP COLUMN $col");
            } catch (Exception) {
                // column might not exist
            }
        }

        // Add virtual generated columns that are NULL for soft-deleted rows
        DB::statement('ALTER TABLE users ADD COLUMN nip_unique VARCHAR(255) GENERATED ALWAYS AS (IF(deleted_at IS NULL, nip, NULL)) STORED');
        DB::statement('ALTER TABLE users ADD COLUMN email_unique VARCHAR(255) GENERATED ALWAYS AS (IF(deleted_at IS NULL, email, NULL)) STORED');
        DB::statement('ALTER TABLE users ADD COLUMN uuid_unique VARCHAR(255) GENERATED ALWAYS AS (IF(deleted_at IS NULL, uuid, NULL)) STORED');

        // Unique indexes on the generated columns — MySQL allows multiple NULLs
        DB::statement('CREATE UNIQUE INDEX users_nip_unique ON users (nip_unique)');
        DB::statement('CREATE UNIQUE INDEX users_email_unique ON users (email_unique)');
        DB::statement('CREATE UNIQUE INDEX users_uuid_unique ON users (uuid_unique)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE users DROP INDEX users_nip_unique');
        DB::statement('ALTER TABLE users DROP INDEX users_email_unique');
        DB::statement('ALTER TABLE users DROP INDEX users_uuid_unique');

        DB::statement('ALTER TABLE users DROP COLUMN uuid_unique');
        DB::statement('ALTER TABLE users DROP COLUMN email_unique');
        DB::statement('ALTER TABLE users DROP COLUMN nip_unique');

        DB::statement('CREATE UNIQUE INDEX users_uuid_unique ON users (uuid)');
        DB::statement('CREATE UNIQUE INDEX users_email_unique ON users (email)');
        DB::statement('CREATE UNIQUE INDEX users_nip_unique ON users (nip)');
    }
};
