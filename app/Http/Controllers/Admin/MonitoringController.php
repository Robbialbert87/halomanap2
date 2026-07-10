<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\WorkflowHistory;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    public function index()
    {
        // ── Card Stats ──────────────────────────────────────────────────────
        $stats = [
            'total'              => Ticket::count(),
            'baru'               => Ticket::where('status', 'Baru')->count(),
            'dalam_penanganan'   => Ticket::where('status', 'Diproses')->count(),
            'menunggu_verifikasi'=> Ticket::where('status', 'Menunggu Verifikasi')->count(),
            'selesai'            => Ticket::where('status', 'Selesai')->count(),
            'sla_breach'         => Ticket::where('sla_breached', true)->count(),
        ];

        // ── Workflow Monitoring (tiket aktif dengan penanggung jawab) ───────
        $activeWorkflows = WorkflowHistory::with([
                'ticket', 'toUser', 'toJabatan', 'toUnit'
            ])
            ->whereNotIn('status', ['didisposisikan', 'eskalasi', 'ditutup', 'selesai'])
            ->latest()
            ->take(20)
            ->get();

        // ── Eskalasi Terbaru ─────────────────────────────────────────────────
        $latestEscalations = WorkflowHistory::with([
                'ticket', 'fromUser', 'toUser', 'fromJabatan', 'toJabatan', 'toUnit'
            ])
            ->where('action', 'eskalasi')
            ->latest()
            ->take(10)
            ->get();

        // ── Pengaduan Terbaru ────────────────────────────────────────────────
        $latestTickets = Ticket::with(['category', 'room'])
            ->latest()
            ->take(10)
            ->get();

        // ── Top & Bottom Unit (berdasarkan rata-rata penyelesaian) ────────────
        $unitPerformance = WorkflowHistory::select('to_unit_id', DB::raw('COUNT(*) as total'), DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, completed_at)) as avg_hours'))
            ->where('action', 'selesai')
            ->whereNotNull('completed_at')
            ->groupBy('to_unit_id')
            ->with('toUnit')
            ->get()
            ->map(fn($row) => [
                'unit'      => $row->toUnit?->nama ?? '-',
                'total'     => $row->total,
                'avg_hours' => round($row->avg_hours, 1),
            ])
            ->sortBy('avg_hours');

        $topUnits    = $unitPerformance->take(5)->values();
        $bottomUnits = $unitPerformance->sortByDesc('avg_hours')->take(5)->values();

        return view('admin.monitoring.index', compact(
            'stats', 'activeWorkflows', 'latestEscalations', 'latestTickets', 'topUnits', 'bottomUnits'
        ));
    }

    public function show(int $ticketId)
    {
        $ticket = Ticket::with(['category', 'room'])->findOrFail($ticketId);

        $timeline = WorkflowHistory::with([
                'fromUser', 'toUser', 'fromJabatan', 'toJabatan', 'fromUnit', 'toUnit'
            ])
            ->where('ticket_id', $ticketId)
            ->orderBy('created_at')
            ->get();

        $activeWorkflow = $timeline->whereNotIn('status', ['didisposisikan', 'eskalasi', 'ditutup', 'selesai'])->last();

        return view('admin.monitoring.show', compact('ticket', 'timeline', 'activeWorkflow'));
    }
}
