<?php

namespace App\Services;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Attempt to authenticate user via NIP.
     */
    public function login(LoginRequest $request): bool
    {
        $credentials = $request->only('nip', 'password');
        
        // We only allow active users to login
        $credentials['status'] = 'active';

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            
            // Update last login
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            // Save Session Data (UUID, NIP, Nama, Role, Jabatan, Unit)
            $request->session()->regenerate();
            $request->session()->put('user_uuid', $user->uuid);
            $request->session()->put('user_nip', $user->nip);
            $request->session()->put('user_nama', $user->nama);
            $request->session()->put('user_role', $user->roles->first()?->name ?? 'Pegawai');
            $request->session()->put('user_jabatan', $user->jabatan);
            $request->session()->put('user_unit', $user->unit?->name);

            return true;
        }

        return false;
    }

    /**
     * Determine redirect route based on role.
     */
    public function getRedirectRoute($user): string
    {
        $role = $user->roles->first()?->name;
        $jabatan = strtolower($user->jabatan?->nama ?? '');

        if ($role === 'Super Admin' || $role === 'Admin Pengaduan') {
            return '/dashboard';
        }

        if ($role === 'Kepala Unit') {
            return '/kepala-unit/dashboard';
        }

        if (str_contains($jabatan, 'kasi') || str_contains($jabatan, 'kasubbag')) {
            return '/kasi/dashboard';
        }

        if (str_contains($jabatan, 'kabid') || str_contains($jabatan, 'kabag')) {
            return '/kabid/dashboard';
        }

        if ($role === 'Direktur' || str_contains($jabatan, 'direktur')) {
            return '/direktur/dashboard';
        }

        return '/dashboard';
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
