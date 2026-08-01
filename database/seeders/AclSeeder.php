<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AclSeeder extends Seeder
{
    /** Permission dasar menu yang dimiliki semua role (role-agnostic). */
    private const BASE_MENU_PERMS = [
        'menu.dashboard',
        'menu.dispositions',
        'menu.dalam-penanganan',
        'menu.riwayat',
        'menu.laporan',
        'menu.profil',
        'manage-dashboard',
        'manage-dispositions',
    ];

    public function run(): void
    {
        $this->seedPermissions();
        $this->seedRoles();
    }

    /**
     * 1. Katalog semua permission yang dikenal aplikasi (key: name).
     */
    private function seedPermissions(): void
    {
        $permissions = [
            // ── Menu / Navigasi (role-agnostic, dipakai route umum) ─────────
            ...self::BASE_MENU_PERMS,

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
            'manage-tickets',
            'manage-units',
            'manage-rooms',
            'manage-categories',
            'manage-users',
            'manage-roles',
            'manage-jabatans',
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

    /**
     * 2. Role & mapping permission (key: name role).
     */
    private function seedRoles(): void
    {
        // ── Super Admin: akses penuh seluruh sistem ────────────────────
        $superAdmin = Role::firstOrCreate(
            ['name' => 'Super Admin', 'guard_name' => 'web'],
            ['kode' => 'SUPER_ADMIN', 'deskripsi' => 'Akses seluruh sistem.', 'status' => 'active']
        );
        $superAdmin->syncPermissions(Permission::all());

        // ── Pegawai: role umum, akses ditentukan jabatan ───────────────
        $pegawai = Role::firstOrCreate(
            ['name' => 'Pegawai', 'guard_name' => 'web'],
            ['kode' => 'PEGAWAI', 'deskripsi' => 'Role umum. Hak akses berdasarkan Jabatan.', 'status' => 'active']
        );
        $pegawai->syncPermissions($this->ensurePermissions(self::BASE_MENU_PERMS));

        // ── Role berbasis Jabatan ──────────────────────────────────────
        $jabatanRoles = [
            'Kepala Unit' => self::BASE_MENU_PERMS,
            'Kasi' => self::BASE_MENU_PERMS,
            'Kasubbag' => self::BASE_MENU_PERMS,
            'Kabid' => self::BASE_MENU_PERMS,
            'Kabag' => self::BASE_MENU_PERMS,
            'Kepala Ruangan' => ['manage-dispositions'],
        ];

        foreach ($jabatanRoles as $name => $perms) {
            $role = Role::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['kode' => strtoupper(str_replace(' ', '_', $name)), 'status' => 'active']
            );
            $role->syncPermissions($this->ensurePermissions($perms));
        }

        // ── Admin Pengaduan: menu & manajemen modul admin ──────────────
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
        $adminRole->syncPermissions($this->ensurePermissions($adminPerms));

        // ── Direktur: dashboard eksekutif & laporan ────────────────────
        $direkturPerms = [
            'menu.direktur', 'menu.dashboard', 'menu.dispositions', 'menu.dalam-penanganan', 'menu.riwayat', 'menu.laporan', 'menu.profil',
            'manage-dashboard', 'manage-dispositions', 'manage-reports',
        ];
        $direkturRole = Role::firstOrCreate(
            ['name' => 'Direktur', 'guard_name' => 'web'],
            ['kode' => 'DIREKTUR', 'status' => 'active']
        );
        $direkturRole->syncPermissions($this->ensurePermissions($direkturPerms));

        $this->command->info('Role-permission mapping seeded: Super Admin, Pegawai, Admin Pengaduan, Direktur, dan role Jabatan.');
    }

    /**
     * Pastikan permission ada (idempotent) lalu kembalikan collection-nya.
     *
     * @param  list<string>  $names
     * @return Collection<int, Permission>
     */
    private function ensurePermissions(array $names): Collection
    {
        foreach ($names as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        return Permission::whereIn('name', $names)->get();
    }
}
