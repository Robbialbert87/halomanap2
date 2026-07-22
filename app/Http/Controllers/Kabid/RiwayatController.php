<?php

namespace App\Http\Controllers\Kabid;

use App\Http\Controllers\Controller;
use App\Models\WorkflowHistory;

class RiwayatController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $workflows = WorkflowHistory::with(['ticket.room.unit', 'ticket.category', 'fromUser', 'toJabatan'])
            ->whereIn('id', function ($q) use ($user) {
                $q->selectRaw('MAX(id)')
                    ->from('workflow_histories')
                    ->where('to_user_id', $user->id)
                    ->whereIn('status', ['selesai', 'eskalasi', 'menunggu_verifikasi'])
                    ->groupBy('ticket_id');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('kabid.riwayat', compact('user', 'workflows'));
    }
}
