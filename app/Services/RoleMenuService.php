<?php

namespace App\Services;

class RoleMenuService
{
    public static function getRoleGroup($user): string
    {
        $role = $user->roles->first()?->name;
        $kategori = $user->jabatan?->kategori_jabatan;

        if ($role === 'Super Admin' || $role === 'Admin Pengaduan') {
            return 'admin';
        }
        if ($kategori === 'Kepala Unit' || $role === 'Kepala Unit') {
            return 'kepala_unit';
        }
        if ($role === 'Kepala Ruangan' || $kategori === 'Kepala Ruangan') {
            return 'head_unit';
        }
        if (in_array($kategori, ['Kasi', 'Kasubbag'])) {
            return 'kasi';
        }
        if (in_array($kategori, ['Kabid', 'Kabag'])) {
            return 'kabid';
        }
        if ($kategori === 'Direktur' || $role === 'Direktur') {
            return 'direktur';
        }

        return 'admin';
    }

    public static function isKepalaUnit($user): bool
    {
        return self::getRoleGroup($user) === 'kepala_unit';
    }

    public static function isKasi($user): bool
    {
        return self::getRoleGroup($user) === 'kasi';
    }

    public static function isKabid($user): bool
    {
        return self::getRoleGroup($user) === 'kabid';
    }

    public static function isDirektur($user): bool
    {
        return self::getRoleGroup($user) === 'direktur';
    }

    public static function isAdmin($user): bool
    {
        return self::getRoleGroup($user) === 'admin';
    }
}
