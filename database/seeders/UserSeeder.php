<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin
        User::firstOrCreate(
            ['email' => 'superadmin@halomanap.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'jabatan' => 'Administrator Sistem',
                'is_active' => true,
            ]
        );

        // 2. Admin Pengaduan
        User::firstOrCreate(
            ['email' => 'admin@halomanap.com'],
            [
                'name' => 'Admin Pengaduan',
                'password' => Hash::make('password'),
                'role' => 'admin_pengaduan',
                'jabatan' => 'Petugas Layanan Pengaduan',
                'is_active' => true,
            ]
        );

        // 3. Kepala Ruangan (contoh: Poli Penyakit Dalam)
        // Assume unit_id 1 is Poli Penyakit Dalam if it exists, otherwise leave null for now
        $unit = Unit::first();
        $unitId = $unit ? $unit->id : null;

        User::firstOrCreate(
            ['email' => 'kepala@halomanap.com'],
            [
                'name' => 'Dr. Budi Kepala',
                'password' => Hash::make('password'),
                'role' => 'kepala_ruangan',
                'unit_id' => $unitId,
                'jabatan' => 'Kepala Unit/Ruangan',
                'is_active' => true,
            ]
        );

        // 4. Petugas Unit
        User::firstOrCreate(
            ['email' => 'petugas@halomanap.com'],
            [
                'name' => 'Petugas Ruangan',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'unit_id' => $unitId,
                'jabatan' => 'Perawat/Petugas',
                'is_active' => true,
            ]
        );
    }
}
