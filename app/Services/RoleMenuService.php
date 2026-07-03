<?php

namespace App\Services;

class RoleMenuService
{
    public static function getRoleGroup($user): string
    {
        $role = $user->roles->first()?->name;
        $jabatan = strtolower($user->jabatan?->nama ?? '');

        if ($role === 'Super Admin' || $role === 'Admin Pengaduan') {
            return 'admin';
        }
        if ($role === 'Kepala Unit' || $role === 'Kepala Ruangan' || str_contains($jabatan, 'kepala unit')) {
            return 'kepala_unit';
        }
        if (str_contains($jabatan, 'kasi') || str_contains($jabatan, 'kasubbag')) {
            return 'kasi';
        }
        if (str_contains($jabatan, 'kabid') || str_contains($jabatan, 'kabag')) {
            return 'kabid';
        }
        if ($role === 'Direktur' || str_contains($jabatan, 'direktur')) {
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
