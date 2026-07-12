<?php

namespace App\Http\Controllers\KepalaUnit;

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
        $dalamProses = (clone $query)->where('status', 'dalam_penanganan')->count();
        $selesai = (clone $query)->whereIn('status', ['selesai', 'ditutup', 'menunggu_verifikasi'])->count();

        $avgRespon = '-';
        $completedTicketIds = (clone $query)
            ->whereIn('status', ['selesai', 'ditutup'])
            ->distinct()
            ->pluck('ticket_id');

        if ($completedTicketIds->isNotEmpty()) {
            $totalHours = 0;
            $count = 0;
            foreach ($completedTicketIds as $ticketId) {
                $first = WorkflowHistory::where('ticket_id', $ticketId)
                    ->where('from_user_id', $user->id)
                    ->whereIn('action', ['disposisi', 'eskalasi'])
                    ->orderBy('created_at')
                    ->value('created_at');
                $last = WorkflowHistory::where('ticket_id', $ticketId)
                    ->whereIn('status', ['selesai', 'ditutup'])
                    ->orderBy('created_at')
                    ->value('created_at');
                if ($first && $last) {
                    $totalHours += $first->diffInHours($last);
                    $count++;
                }
            }
            if ($count > 0) {
                $avgRespon = round($totalHours / $count, 1) . ' Jam';
            }
        }

        $latestWorkflows = (clone $query)
            ->with(['ticket.room.unit', 'ticket.category', 'toUser', 'toJabatan'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('kepala_unit.dashboard', compact('user', 'baru', 'dalamProses', 'selesai', 'avgRespon', 'latestWorkflows'));
    }
}
