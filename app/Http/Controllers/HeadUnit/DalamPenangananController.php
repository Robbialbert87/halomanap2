<?php

namespace App\Http\Controllers\HeadUnit;

use App\Http\Controllers\Controller;
use App\Models\WorkflowHistory;

class DalamPenangananController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $workflows = WorkflowHistory::with(['ticket.room.unit', 'ticket.category', 'fromUser', 'toJabatan'])
            ->where('to_user_id', $user->id)
            ->where('status', 'dalam_penanganan')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('head_unit.dalam_penanganan', compact('user', 'workflows'));
    }
}
