<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $unit = Unit::first();
        $unitId = $unit ? $unit->id : null;

        $roleAdmin = Role::firstOrCreate(
            ['name' => 'Admin Pengaduan', 'guard_name' => 'web'],
            ['kode' => 'ADMIN_PENGADUAN', 'status' => 'active']
        );

        $users = [
            [
                'email' => 'superadmin@halomanap.com',
                'nama' => 'Super Admin',
                'phone_number' => '081111111111',
                'password' => Hash::make('password'),
                'status' => 'active',
                'unit_id' => null,
                'jabatan_id' => null,
                'role' => 'Super Admin',
            ],
            [
                'email' => 'admin@halomanap.com',
                'nama' => 'Admin Pengaduan',
                'phone_number' => '082222222222',
                'password' => Hash::make('password'),
                'status' => 'active',
                'unit_id' => null,
                'jabatan_id' => null,
                'role' => $roleAdmin->name,
            ],
            [
                'email' => 'kepala@halomanap.com',
                'nama' => 'Dr. Budi Kepala',
                'phone_number' => '083333333333',
                'password' => Hash::make('password'),
                'status' => 'active',
                'unit_id' => $unitId,
                'jabatan_id' => null,
                'role' => 'Pegawai',
            ],
            [
                'email' => 'petugas@halomanap.com',
                'nama' => 'Petugas Ruangan',
                'phone_number' => '084444444444',
                'password' => Hash::make('password'),
                'status' => 'active',
                'unit_id' => $unitId,
                'jabatan_id' => null,
                'role' => 'Pegawai',
            ],
        ];

        foreach ($users as $data) {
            $roleName = $data['role'];
            unset($data['role']);

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                $data
            );
            $user->syncRoles([$roleName]);
        }

        $this->command->info('4 user akun berhasil dibuat (email login).');
    }
}
