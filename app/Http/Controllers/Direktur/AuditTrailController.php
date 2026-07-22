<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use Illuminate\Http\Request;

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
