<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'Super Admin', 'guard_name' => 'web'],
            ['kode' => 'SUPER_ADMIN', 'deskripsi' => 'Akses seluruh sistem.', 'status' => 'active']
        );
        Role::firstOrCreate(
            ['name' => 'Admin Pengaduan', 'guard_name' => 'web'],
            ['kode' => 'ADMIN_PENGADUAN', 'deskripsi' => 'Mengelola seluruh pengaduan dan disposisi.', 'status' => 'active']
        );
        Role::firstOrCreate(
            ['name' => 'Pegawai', 'guard_name' => 'web'],
            ['kode' => 'PEGAWAI', 'deskripsi' => 'Role umum. Hak akses ditentukan berdasarkan Jabatan.', 'status' => 'active']
        );
        Role::firstOrCreate(
            ['name' => 'Direktur', 'guard_name' => 'web'],
            ['kode' => 'DIREKTUR', 'deskripsi' => 'Hanya monitoring. Tidak mengubah data.', 'status' => 'active']
        );

        // Create superadmin user based on NIP
        $nip = '198706072020121003';
        $user = User::updateOrCreate(
            ['nip' => $nip],
            [
                'nama'      => 'Super Admin',
                'password'  => Hash::make($nip),
                'status'    => 'active',
            ]
        );

        // Assign role
        $user->syncRoles([$superAdminRole->name]);

        // Give all permissions to Super Admin
        $superAdminRole->syncPermissions(Permission::all());

        $this->command->info("Super Admin created: NIP $nip / Password $nip");
    }
}
