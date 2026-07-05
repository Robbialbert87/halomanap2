<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\WorkflowHistory;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalTickets   = Ticket::count();
        $diproses       = Ticket::whereIn('status', ['Diproses', 'IN_PROGRESS'])->count();
        $selesai        = Ticket::where('status', 'Selesai')->count();
        $slaBreach      = Ticket::where('sla_breached', true)->count();

        $activeWorkflows = WorkflowHistory::with(['ticket', 'toUser', 'toJabatan', 'toUnit'])
            ->whereNotIn('status', ['eskalasi', 'ditutup', 'selesai'])
            ->latest()
            ->take(10)
            ->get();

        $unitStats = WorkflowHistory::select('to_unit_id', DB::raw('COUNT(*) as total'), DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, completed_at)) as avg_hours'))
            ->where('action', 'selesai')
            ->whereNotNull('completed_at')
            ->groupBy('to_unit_id')
            ->with('toUnit')
            ->get()
            ->map(fn($r) => ['unit' => $r->toUnit?->nama ?? '-', 'total' => $r->total, 'avg_hours' => round($r->avg_hours, 1)])
            ->sortBy('avg_hours');

        $topUnits    = $unitStats->take(5)->values();
        $bottomUnits = $unitStats->sortByDesc('avg_hours')->take(5)->values();

        return view('direktur.dashboard', compact(
            'user', 'totalTickets', 'diproses', 'selesai', 'slaBreach',
            'activeWorkflows', 'topUnits', 'bottomUnits'
        ));
    }
}