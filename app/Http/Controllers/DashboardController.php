<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\WorkflowHistory;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $baseQuery = fn ($q) => $q->where('id', $user->unit_id);

        $total = Ticket::whereHas('room.unit', $baseQuery)->count();

        $menunggu = Ticket::whereHas('room.unit', $baseQuery)
            ->whereIn('status', ['NEW', 'TERVERIFIKASI'])
            ->count();

        $diproses = Ticket::whereHas('room.unit', $baseQuery)
            ->where('status', 'IN_PROGRESS')
            ->count();

        $selesai = Ticket::whereHas('room.unit', $baseQuery)
            ->where('status', 'DONE')
            ->count();

        $ditolak = 0;

        $pMenunggu = $total > 0 ? round(($menunggu / $total) * 100) : 0;
        $pDiproses = $total > 0 ? round(($diproses / $total) * 100) : 0;
        $pSelesai = $total > 0 ? round(($selesai / $total) * 100) : 0;
        $pDitolak = $total > 0 ? round(($ditolak / $total) * 100) : 0;

        $baru = Ticket::whereHas('room.unit', $baseQuery)
            ->whereIn('status', ['NEW', 'TERVERIFIKASI'])
            ->whereNull('notification_seen_at')
            ->count();

        $query = WorkflowHistory::where('from_user_id', $user->id)
            ->whereIn('status', ['selesai', 'ditutup']);

        $completedTicketIds = (clone $query)->distinct()->pluck('ticket_id');

        $avgRespon = '-';
        if ($completedTicketIds->isNotEmpty()) {
            $totalHours = 0;
            $count = 0;
            foreach ($completedTicketIds as $ticketId) {
                $first = WorkflowHistory::where('ticket_id', $ticketId)
                    ->where('from_user_id', $user->id)
                    ->whereIn('action', ['disposisi', 'eskalasi'])
                    ->orderBy('created_at')
                    ->value('created_at');
                $last = (clone $query)->where('ticket_id', $ticketId)
                    ->orderBy('created_at')
                    ->value('created_at');
                if ($first && $last) {
                    $totalHours += $first->diffInHours($last);
                    $count++;
                }
            }
            if ($count > 0) {
                $avgRespon = round($totalHours / $count, 1).' Jam';
            }
        }

        $dalamProses = WorkflowHistory::where('to_user_id', $user->id)
            ->where('status', 'dalam_penanganan')
            ->count();

        $monitoring = null;
        if ($user->can('menu.kabid.monitoring')) {
            $monitoring = Ticket::whereHas('room.unit', $baseQuery)
                ->whereNull('notification_seen_at')
                ->count();
        }

        $latestWorkflows = WorkflowHistory::with(['ticket.room.unit', 'ticket.category', 'fromUser', 'toJabatan'])
            ->where('to_user_id', $user->id)
            ->whereNotIn('status', ['selesai', 'ditutup'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $isAdmin = $user->can('manage-tickets') || $user->hasRole('Super Admin');

        $categoryData = Ticket::selectRaw('category_id, COUNT(*) as total')
            ->whereHas('room.unit', $baseQuery)
            ->whereNotNull('category_id')
            ->groupBy('category_id')
            ->with('category:id,name')
            ->get()
            ->map(fn ($t) => ['category' => $t->category ? ['name' => $t->category->name] : null, 'total' => (int) $t->total]);

        $categoryColors = ['#8B5CF6', '#F59E0B', '#3B82F6', '#10B981', '#EF4444', '#EC4899', '#14B8A6', '#F97316'];

        $monthlyTickets = Ticket::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->whereHas('room.unit', $baseQuery)
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $key = $month->format('Y-m');
            $monthlyLabels[] = $month->format('M Y');
            $monthlyData[] = (int) ($monthlyTickets[$key] ?? 0);
        }

        if ($isAdmin && $user->unit_id) {
            $unitData = Ticket::selectRaw('units.nama, COUNT(*) as total')
                ->join('rooms', 'tickets.room_id', '=', 'rooms.id')
                ->join('units', 'rooms.unit_id', '=', 'units.id')
                ->groupBy('units.id', 'units.nama')
                ->pluck('total', 'nama')
                ->toArray();
        } else {
            $unitName = $user->unit?->nama ?? 'Unit Saya';
            $unitData = [$unitName => $total];
        }

        return view('dashboard', ['user' => $user, 'total' => $total, 'baru' => $baru, 'menunggu' => $menunggu, 'diproses' => $diproses, 'selesai' => $selesai, 'ditolak' => $ditolak, 'pMenunggu' => $pMenunggu, 'pDiproses' => $pDiproses, 'pSelesai' => $pSelesai, 'pDitolak' => $pDitolak, 'dalamProses' => $dalamProses, 'avgRespon' => $avgRespon, 'monitoring' => $monitoring, 'latestWorkflows' => $latestWorkflows, 'categoryData' => $categoryData, 'categoryColors' => $categoryColors, 'unitData' => $unitData, 'monthlyData' => $monthlyData, 'monthlyLabels' => $monthlyLabels]);
    }
}
