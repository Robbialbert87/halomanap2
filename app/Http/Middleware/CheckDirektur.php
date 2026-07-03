<?php

namespace App\Http\Middleware;

use App\Models\OrganizationHierarchy;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDirektur
{
    /**
     * Cek apakah user yang login adalah Direktur.
     * "Direktur" ditentukan berdasarkan jabatan yang memiliki is_workflow_end = true,
     * atau memiliki role DIREKTUR — bukan hardcode nama jabatan.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Cek via Role Spatie
        if ($user->hasRole(['SUPER_ADMIN', 'DIREKTUR'])) {
            return $next($request);
        }

        // Cek via jabatan yang ditandai is_workflow_end di structure org
        if ($user->jabatan_id) {
            $isEndPoint = OrganizationHierarchy::where('jabatan_id', $user->jabatan_id)
                ->where('is_workflow_end', true)
                ->where('status', 'active')
                ->exists();

            if ($isEndPoint) {
                return $next($request);
            }
        }

        abort(403, 'Akses khusus Direktur.');
    }
}
