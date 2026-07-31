<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // ── Menu / Navigasi (role-agnostic, dipakai route umum) ─────────
            'menu.dashboard',
            'menu.dispositions',
            'menu.dalam-penanganan',
            'menu.riwayat',
            'menu.laporan',
            'menu.profil',

            'menu.admin.tickets',
            'menu.admin.units',
            'menu.admin.rooms',
            'menu.admin.categories',
            'menu.admin.users',
            'menu.admin.roles',
            'menu.admin.jabatans',
            'menu.admin.workflow',
            'menu.admin.monitoring',

            'menu.direktur.dashboard',
            'menu.direktur.monitoring-workflow',
            'menu.direktur.statistik',
            'menu.direktur.laporan',
            'menu.direktur.audit-trail',
            'menu.direktur.profil',

            // ── Aksi / CRUD ───────────────────────────────────────────────
            'manage-dashboard',
            'manage-tickets',
            'manage-units',
            'manage-rooms',
            'manage-categories',
            'manage-users',
            'manage-roles',
            'manage-jabatans',
            'manage-dispositions',
            'manage-reports',
            'manage-settings',
            'manage-audit-trail',
            'manage-whatsapp',
            'view-statistics',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }
    }
}
