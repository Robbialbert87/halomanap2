<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // 1. Izin & role (katalog permission + mapping role)
            AclSeeder::class,

            // 2. Master data: SLA, kategori, unit, ruangan, jabatan
            MasterDataSeeder::class,

            // 3. Akun: super admin & pengguna inti (login via NIP)
            UserSeeder::class,

            // 4. Konfigurasi gateway WhatsApp
            WahaSettingSeeder::class,

            // 5. Data dummy pengaduan/apresiasi untuk demo & testing
            DummyTicketSeeder::class,
        ]);
    }
}
