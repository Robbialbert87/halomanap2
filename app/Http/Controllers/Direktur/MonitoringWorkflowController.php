<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Unit;
use App\Models\ReportCategory;
use Illuminate\Http\Request;

class MonitoringWorkflowController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['room.unit', 'category'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('unit_id')) {
            $query->whereHas('room', fn($q) => $q->where('unit_id', $request->unit_id));
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('ticket_number', 'like', '%' . $request->search . '%')
                  ->orWhere('title', 'like', '%' . $request->search . '%')
                  ->orWhere('reporter_name', 'like', '%' . $request->search . '%');
            });
        }

        $tickets   = $query->paginate(15)->withQueryString();
        $units     = Unit::orderBy('nama')->get();
        $statuses  = ['Baru', 'TERVERIFIKASI', 'Diproses', 'Menunggu Verifikasi', 'Selesai', 'Ditolak'];
        $user      = auth()->user();

        $counts = [
            'total'    => Ticket::count(),
            'baru'     => Ticket::whereIn('status', ['Baru', 'NEW'])->count(),
            'diproses' => Ticket::whereIn('status', ['Diproses', 'IN_PROGRESS'])->count(),
            'selesai'  => Ticket::where('status', 'Selesai')->count(),
        ];

        return view('direktur.monitoring_workflow', compact('tickets', 'units', 'statuses', 'user', 'counts'));
    }
}