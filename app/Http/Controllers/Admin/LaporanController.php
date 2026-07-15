<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportCategory;
use App\Models\Ticket;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['room.unit', 'category']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('unit_id')) {
            $query->whereHas('room', fn($q) => $q->where('unit_id', $request->unit_id));
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->get();

        $total = $tickets->count();
        $baru = $tickets->where('status', 'Baru')->count();
        $diproses = $tickets->where('status', 'Diproses')->count();
        $menungguVerifikasi = $tickets->where('status', 'Menunggu Verifikasi')->count();
        $selesai = $tickets->where('status', 'Selesai')->count();
        $ditolak = $tickets->where('status', 'Ditolak')->count();

        $units = Unit::where('status', 'active')->orderBy('nama')->get();
        $categories = ReportCategory::orderBy('name')->get();

        $statuses = ['Baru', 'Diproses', 'Menunggu Verifikasi', 'Selesai', 'Ditolak'];

        return view('admin.laporan.index', compact(
            'tickets', 'total', 'baru', 'diproses', 'menungguVerifikasi', 'selesai', 'ditolak',
            'units', 'categories', 'statuses'
        ));
    }

    public function exportPdf(Request $request)
    {
        $query = Ticket::with(['room.unit', 'category']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('unit_id')) {
            $query->whereHas('room', fn($q) => $q->where('unit_id', $request->unit_id));
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->get();

        $total = $tickets->count();
        $baru = $tickets->where('status', 'Baru')->count();
        $diproses = $tickets->where('status', 'Diproses')->count();
        $menungguVerifikasi = $tickets->where('status', 'Menunggu Verifikasi')->count();
        $selesai = $tickets->where('status', 'Selesai')->count();
        $ditolak = $tickets->where('status', 'Ditolak')->count();

        $periode = '';
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $periode = Carbon::parse($request->start_date)->format('d/m/Y') . ' - ' . Carbon::parse($request->end_date)->format('d/m/Y');
        } elseif ($request->filled('start_date')) {
            $periode = 'Dari ' . Carbon::parse($request->start_date)->format('d/m/Y');
        } elseif ($request->filled('end_date')) {
            $periode = 'Sampai ' . Carbon::parse($request->end_date)->format('d/m/Y');
        } else {
            $periode = 'Semua Periode';
        }

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'tickets', 'total', 'baru', 'diproses', 'menungguVerifikasi', 'selesai', 'ditolak', 'periode'
        ));

        $filename = 'laporan-pengaduan-' . Carbon::now()->format('Ymd-His') . '.pdf';
        return $pdf->download($filename);
    }
}
