<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        $auditTrails = AuditTrail::with('user')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = trim($request->search);
                $q->where(function ($sub) use ($search) {
                    $sub->where('action', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($u) => $u->where('nama', 'like', "%{$search}%"));
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Live filter: balas fragment (tabel) untuk fetch tanpa reload
        return view('direktur.audit_trail', ['auditTrails' => $auditTrails, 'user' => auth()->user()])
            ->fragmentIf($request->header('X-Live-Filter') === '1', 'results');
    }
}
