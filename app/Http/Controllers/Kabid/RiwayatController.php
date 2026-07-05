<?php

namespace App\Http\Controllers\Kabid;

use App\Http\Controllers\Controller;
use App\Models\WorkflowHistory;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $workflows = WorkflowHistory::with(['ticket.room.unit', 'ticket.category', 'fromUser', 'toJabatan'])
            ->where('to_user_id', $user->id)
            ->whereIn('status', ['selesai', 'ditutup', 'eskalasi', 'menunggu_verifikasi'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('kabid.riwayat', compact('user', 'workflows'));
    }
}