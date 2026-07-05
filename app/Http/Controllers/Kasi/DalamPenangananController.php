<?php

namespace App\Http\Controllers\Kasi;

use App\Http\Controllers\Controller;
use App\Models\WorkflowHistory;
use Illuminate\Http\Request;

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

        return view('kasi.dalam_penanganan', compact('user', 'workflows'));
    }
}