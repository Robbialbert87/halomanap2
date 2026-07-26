<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $jabatanRoles = [
            'Kepala Unit' => ['menu.dashboard', 'menu.dispositions', 'menu.dalam-penanganan', 'menu.riwayat', 'menu.laporan', 'menu.profil', 'manage-dashboard', 'manage-dispositions'],
            'Kasi' => ['menu.dashboard', 'menu.dispositions', 'menu.dalam-penanganan', 'menu.riwayat', 'menu.laporan', 'menu.profil', 'manage-dashboard', 'manage-dispositions'],
            'Kasubbag' => ['menu.dashboard', 'menu.dispositions', 'menu.dalam-penanganan', 'menu.riwayat', 'menu.laporan', 'menu.profil', 'manage-dashboard', 'manage-dispositions'],
            'Kabid' => ['menu.dashboard', 'menu.dispositions', 'menu.dalam-penanganan', 'menu.riwayat', 'menu.laporan', 'menu.profil', 'manage-dashboard', 'manage-dispositions'],
            'Kabag' => ['menu.dashboard', 'menu.dispositions', 'menu.dalam-penanganan', 'menu.riwayat', 'menu.laporan', 'menu.profil', 'manage-dashboard', 'manage-dispositions'],
            'Kepala Ruangan' => ['manage-dispositions'],
        ];

        foreach ($jabatanRoles as $name => $perms) {
            $role = Role::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['kode' => strtoupper(str_replace(' ', '_', $name)), 'status' => 'active']
            );
            $role->syncPermissions(array_map(fn ($p) => Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']), $perms));
        }

        $adminPerms = [
            'menu.admin.tickets', 'menu.admin.units', 'menu.admin.rooms', 'menu.admin.categories',
            'menu.admin.users', 'menu.admin.roles', 'menu.admin.jabatans', 'menu.admin.workflow', 'menu.admin.monitoring',
            'manage-dashboard', 'manage-tickets', 'manage-dispositions', 'manage-reports', 'manage-settings', 'manage-whatsapp',
            'menu.dashboard', 'menu.dispositions', 'menu.dalam-penanganan', 'menu.riwayat', 'menu.laporan', 'menu.profil',
        ];
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin Pengaduan', 'guard_name' => 'web'],
            ['kode' => 'ADMIN_PENGADUAN', 'status' => 'active']
        );
        $adminRole->syncPermissions(array_map(fn ($p) => Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']), $adminPerms));

        $direkturPerms = [
            'menu.direktur', 'menu.dashboard', 'menu.dispositions', 'menu.dalam-penanganan', 'menu.riwayat', 'menu.laporan', 'menu.profil',
            'manage-dashboard', 'manage-dispositions', 'manage-reports',
        ];
        $direkturRole = Role::firstOrCreate(
            ['name' => 'Direktur', 'guard_name' => 'web'],
            ['kode' => 'DIREKTUR', 'status' => 'active']
        );
        $direkturRole->syncPermissions(array_map(fn ($p) => Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']), $direkturPerms));

        // Ensure permissions exist for any roles assigned directly via Spatie
        $allPermissions = array_unique(array_merge(
            ...array_values(array_map(fn ($r) => $r['perms'], [
                ['perms' => $jabatanRoles['Kepala Unit']],
                ['perms' => $adminPerms],
                ['perms' => $direkturPerms],
            ]))
        ));
        $this->command->info('Role-permission mapping seeded.');
    }
}
