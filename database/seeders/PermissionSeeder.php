<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Admin Master Data
            'menu.admin.tickets',
            'menu.admin.units',
            'menu.admin.rooms',
            'menu.admin.categories',
            'menu.admin.users',
            'menu.admin.roles',
            'menu.admin.jabatans',
            'menu.admin.organization-hierarchies',
            'menu.admin.workflow',
            'menu.admin.monitoring',

            // Kepala Unit
            'menu.kepala-unit.dashboard',
            'menu.kepala-unit.dispositions',
            'menu.kepala-unit.dalam-penanganan',
            'menu.kepala-unit.riwayat',
            'menu.kepala-unit.laporan',

            // Kasi / Kasubbag
            'menu.kasi.dashboard',
            'menu.kasi.dispositions',
            'menu.kasi.dalam-penanganan',
            'menu.kasi.riwayat',
            'menu.kasi.laporan',

            // Kabid / Kabag
            'menu.kabid.dashboard',
            'menu.kabid.dispositions',
            'menu.kabid.monitoring',
            'menu.kabid.laporan',

            // Direktur
            'menu.direktur.dashboard',
            'menu.direktur.monitoring-workflow',
            'menu.direktur.statistik',
            'menu.direktur.laporan',
            'menu.direktur.audit-trail',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }
    }
}
