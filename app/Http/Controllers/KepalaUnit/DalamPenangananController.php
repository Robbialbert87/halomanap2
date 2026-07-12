<?php

namespace App\Http\Controllers\KepalaUnit;

use App\Http\Controllers\Controller;
use App\Models\WorkflowHistory;
use Illuminate\Http\Request;

class DalamPenangananController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $workflows = WorkflowHistory::with(['ticket.room.unit', 'ticket.category', 'fromUser', 'toJabatan', 'toUser'])
            ->where('from_user_id', $user->id)
            ->whereIn('status', ['menunggu_respon', 'dalam_penanganan'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('kepala_unit.dalam_penanganan', compact('user', 'workflows'));
    }
}