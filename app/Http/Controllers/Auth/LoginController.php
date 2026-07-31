<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect($this->getRedirectRoute(Auth::user()));
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('nip', 'password');
        $credentials['status'] = 'active';

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            $request->session()->regenerate();
            $request->session()->put('user_uuid', $user->uuid);
            $request->session()->put('user_nip', $user->nip);
            $request->session()->put('user_nama', $user->nama);
            $request->session()->put('user_role', $user->roles->first()?->name ?? 'Pegawai');
            $request->session()->put('user_jabatan', $user->jabatan);
            $request->session()->put('user_unit', $user->unit?->name);

            return redirect()->intended($this->getRedirectRoute($user));
        }

        return back()->withErrors([
            'nip' => 'NIP atau Password tidak sesuai.',
        ])->onlyInput('nip');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function getRedirectRoute($user): string
    {
        $role = $user->roles->first()?->name;
        $kategori = $user->jabatan?->kategori_jabatan;

        if ($role === 'Super Admin' || $role === 'Admin Pengaduan') {
            return '/dashboard';
        }

        // Role-agnostic: semua jabatan pelayanan memakai dashboard umum
        // (route role-spesifik lama sudah dihapus pada refactor).
        if (in_array($kategori, ['Kepala Unit', 'Kasi', 'Kasubbag', 'Kabid', 'Kabag'])) {
            return '/dashboard';
        }

        if ($kategori === 'Direktur' || $role === 'Direktur') {
            return '/direktur/dashboard';
        }

        return '/dashboard';
    }
}
