<?php

namespace App\Http\Controllers;

use App\Models\WorkflowHistory;

class DalamPenangananController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $roleGroup = $user->getRoleGroup();

        $query = WorkflowHistory::with(['ticket.room.unit', 'ticket.category', 'fromUser', 'toJabatan']);

        if ($roleGroup === 'kepala_unit') {
            $query->with('toUser')
                ->where('from_user_id', $user->id)
                ->whereIn('status', ['menunggu_respon', 'dalam_penanganan']);
        } else {
            $query->where('to_user_id', $user->id)
                ->where('status', 'dalam_penanganan');
        }

        $workflows = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('dalam_penanganan', compact('user', 'workflows'));
    }
}
