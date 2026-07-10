<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDirektur
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasRole(['Super Admin', 'Admin Pengaduan', 'Direktur'])) {
            return $next($request);
        }

        if ($user->jabatan?->kategori_jabatan === 'Direktur') {
            return $next($request);
        }

        abort(403, 'Akses khusus Direktur.');
    }
}
