<?php

namespace App\Http\Controllers\Kabid;

use App\Http\Controllers\Controller;
use App\Models\WorkflowHistory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = WorkflowHistory::where('from_user_id', $user->id)
            ->whereIn('action', ['disposisi', 'eskalasi']);

        $baru = (clone $query)->where('status', 'menunggu_respon')->count();
        $selesai = (clone $query)->whereIn('status', ['selesai', 'ditutup', 'menunggu_verifikasi'])->count();
        $monitoring = (clone $query)->whereNotIn('status', ['selesai', 'ditutup'])->count();

        $latestWorkflows = (clone $query)
            ->with(['ticket.room.unit', 'ticket.category', 'toUser', 'toJabatan'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('kabid.dashboard', compact('user', 'baru', 'selesai', 'monitoring', 'latestWorkflows'));
    }
}
