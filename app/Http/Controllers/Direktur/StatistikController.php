<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\WorkflowHistory;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalTickets  = Ticket::count();
        $statusCounts  = Ticket::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $diproses      = ($statusCounts['Diproses'] ?? 0) + ($statusCounts['IN_PROGRESS'] ?? 0);
        $selesai       = $statusCounts['Selesai'] ?? 0;
        $baru          = ($statusCounts['Baru'] ?? 0) + ($statusCounts['NEW'] ?? 0);
        $slaBreach     = Ticket::where('sla_breached', true)->count();

        $perUnit = WorkflowHistory::select('to_unit_id', DB::raw('COUNT(*) as total'))
            ->groupBy('to_unit_id')
            ->with('toUnit')
            ->get()
            ->map(fn($r) => ['unit' => $r->toUnit?->nama ?? '-', 'total' => $r->total])
            ->sortByDesc('total')
            ->values();

        return view('direktur.statistik', compact(
            'user', 'totalTickets', 'diproses', 'selesai', 'baru', 'slaBreach', 'perUnit'
        ));
    }
}