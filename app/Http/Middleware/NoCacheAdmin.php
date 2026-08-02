<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Larangkan caching halaman HTML area admin.
 *
 * Menghindari tampilan "halaman rusak" saat navigasi karena service worker
 * atau browser menyajikan HTML lama dari cache (stale-while-revalidate /
 * back-forward cache). Semua halaman admin harus selalu di-reload penuh.
 */
class NoCacheAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        if ($response->isSuccessful() || $response->isRedirect()) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}
