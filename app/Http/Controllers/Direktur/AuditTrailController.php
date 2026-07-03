<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditTrail;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $auditTrails = AuditTrail::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('direktur.audit_trail', compact('auditTrails', 'user'));
    }
}
