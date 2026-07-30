<?php

namespace App\Http\Controllers;

use App\Models\WorkflowHistory;

class RiwayatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $workflows = WorkflowHistory::with(['ticket.room.unit', 'ticket.category', 'fromUser', 'toJabatan'])
            ->where('to_user_id', $user->id)
            ->whereIn('status', ['selesai', 'ditutup', 'menunggu_verifikasi'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('riwayat', ['user' => $user, 'workflows' => $workflows]);
    }
}
