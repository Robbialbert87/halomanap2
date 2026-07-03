<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\ReportCategory;
use App\Models\Unit;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['room.unit', 'category'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by unit (via room)
        if ($request->filled('unit_id')) {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            });
        }

        // Search by ticket number or title
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('ticket_number', 'like', '%' . $request->search . '%')
                  ->orWhere('title', 'like', '%' . $request->search . '%')
                  ->orWhere('reporter_name', 'like', '%' . $request->search . '%');
            });
        }

        $tickets = $query->paginate(15)->withQueryString();
        $units = Unit::orderBy('nama')->get();
        $categories = ReportCategory::orderBy('name')->get();

        // Summary counts
        $counts = [
            'total'    => Ticket::count(),
            'new'      => Ticket::where('status', 'NEW')->count(),
            'verified' => Ticket::where('status', 'TERVERIFIKASI')->count(),
            'process'  => Ticket::whereIn('status', ['Diproses', 'IN_PROGRESS', 'Menunggu Verifikasi'])->count(),
            'done'     => Ticket::where('status', 'Selesai')->count(),
            'rejected' => Ticket::where('status', 'REJECTED')->count(),
        ];

        return view('admin.tickets.index', compact('tickets', 'units', 'categories', 'counts'));
    }

    public function show(string $id)
    {
        $ticket = Ticket::with([
            'room.unit', 'category', 'histories.user', 'comments.user', 
            'attachments.user', 'disposition.unit', 'disposition.headUser',
            'workflows.fromUser', 'workflows.toUser', 'workflows.toUnit', 'workflows.toJabatan'
        ])->findOrFail($id);
        
        // Load additional data for disposition modal
        $units = \App\Models\Unit::orderBy('nama')->get();
        $headUsers = \App\Models\User::orderBy('nama')->get(); // Diupdate karena 'role' tidak ada lagi di users table, pakai Spatie nantinya

        return view('admin.tickets.show', compact('ticket', 'units', 'headUsers'));
    }

    public function verify(Request $request, string $id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->status !== 'NEW') {
            return redirect()->back()->with('error', 'Hanya pengaduan dengan status Baru yang dapat diverifikasi.');
        }

        $ticket->update(['status' => 'TERVERIFIKASI']);

        $ticket->histories()->create([
            'user_id'    => auth()->id(),
            'old_status' => 'NEW',
            'new_status' => 'TERVERIFIKASI',
            'notes'      => 'Pengaduan diverifikasi oleh Admin.' . ($request->notes ? ' Catatan: ' . $request->notes : ''),
        ]);

        return redirect()->route('admin.tickets.show', $ticket->id)
            ->with('success', 'Pengaduan #' . $ticket->ticket_number . ' berhasil diverifikasi.');
    }

    public function reject(Request $request, string $id)
    {
        $request->validate(['notes' => 'nullable|string']);

        $ticket = Ticket::findOrFail($id);

        if ($ticket->status !== 'NEW') {
            return redirect()->back()->with('error', 'Hanya pengaduan dengan status Baru yang dapat ditolak.');
        }

        $ticket->update(['status' => 'REJECTED']);

        $ticket->histories()->create([
            'user_id'    => auth()->id(),
            'old_status' => 'NEW',
            'new_status' => 'REJECTED',
            'notes'      => 'Pengaduan ditolak oleh Admin.' . ($request->notes ? ' Alasan: ' . $request->notes : ''),
        ]);

        return redirect()->route('admin.tickets.show', $ticket->id)
            ->with('success', 'Pengaduan #' . $ticket->ticket_number . ' ditolak.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:NEW,TERVERIFIKASI,IN_PROGRESS,DONE,REJECTED',
            'notes' => 'nullable|string',
        ]);

        $ticket = Ticket::findOrFail($id);
        $oldStatus = $ticket->status;
        $newStatus = $request->status;

        if ($oldStatus !== $newStatus) {
            $ticket->update(['status' => $newStatus]);
            
            $ticket->histories()->create([
                'user_id' => auth()->id(), // Akan null jika belum ada sistem login, tidak masalah.
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'notes' => $request->notes,
            ]);
        }

        return redirect()->route('admin.tickets.show', $ticket->id)
            ->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    public function create() {}
    public function store(Request $request) {}
    public function edit(string $id) {}

    public function destroy(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Pengaduan #' . $ticket->ticket_number . ' berhasil dihapus.');
    }
}
