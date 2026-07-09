<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Room;
use App\Models\ReportCategory;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $tickets = $query->paginate(7)->withQueryString()->onEachSide(2);
        $units = Unit::orderBy('nama')->get();
        $categories = ReportCategory::orderBy('name')->get();

        return view('admin.tickets.index', compact('tickets', 'units', 'categories'));
    }

    public function mobileSearch(Request $request)
    {
        $query = Ticket::with(['room.unit', 'category'])->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('ticket_number', 'like', "%{$s}%")
                  ->orWhere('title', 'like', "%{$s}%")
                  ->orWhere('reporter_name', 'like', "%{$s}%");
            });
        }

        $tickets = $query->paginate(7)->withQueryString()->onEachSide(2);

        $statusMap = [
            'NEW'                 => ['label' => 'Baru',     'class' => 'bg-yellow-100 text-yellow-700'],
            'TERVERIFIKASI'       => ['label' => 'Terverifikasi', 'class' => 'bg-cyan-100 text-cyan-700'],
            'IN_PROGRESS'         => ['label' => 'Diproses', 'class' => 'bg-blue-100 text-blue-700'],
            'DONE'                => ['label' => 'Selesai',  'class' => 'bg-green-100 text-green-700'],
            'REJECTED'            => ['label' => 'Ditolak',  'class' => 'bg-red-100 text-red-700'],
            'Diproses'            => ['label' => 'Diproses', 'class' => 'bg-blue-100 text-blue-700'],
            'Menunggu Verifikasi' => ['label' => 'Menunggu Verifikasi', 'class' => 'bg-purple-100 text-purple-700'],
            'Selesai'             => ['label' => 'Selesai',  'class' => 'bg-green-100 text-green-700'],
        ];

        $html = view('admin.tickets._mobile_list', compact('tickets', 'statusMap'))->render();

        return response()->json(['html' => $html]);
    }

    public function show(string $id)
    {
        $ticket = Ticket::with([
            'room.unit', 'category', 'histories.user', 'comments.user', 
            'attachments.user', 'disposition.unit', 'disposition.headUser',
            'workflows.fromUser', 'workflows.toUser', 'workflows.toUnit', 'workflows.toJabatan'
        ])->findOrFail($id);
        
        // Mark notification as seen when admin views a NEW ticket
        if ($ticket->status === 'NEW' && !$ticket->notification_seen_at) {
            $ticket->update(['notification_seen_at' => now()]);
        }
        
        $units = \App\Models\Unit::orderBy('nama')->get();
        $headUsers = \App\Models\User::orderBy('nama')->get();

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

    public function create()
    {
        $units      = Unit::where('status', 'active')->orderBy('nama')->get();
        $rooms      = Room::orderBy('name')->get()->groupBy('unit_id');
        $categories = ReportCategory::orderBy('name')->get();

        return view('admin.tickets.create', compact('units', 'rooms', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'           => 'required|in:Pengaduan,Survei,Apresiasi,Informasi',
            'unit_id'        => 'required|exists:units,id',
            'room_id'        => 'required|exists:rooms,id',
            'category_id'    => 'required|exists:report_categories,id',
            'reporter_name'  => 'required|string|max:255',
            'reporter_phone' => 'required|string|max:20',
            'title'          => 'required|string|max:255',
            'description'    => 'required|string|min:10',
            'attachment'     => 'nullable|file|mimes:jpg,jpeg,png,pdf,heic,heif,webp|max:20480',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            if ($file->isValid()) {
                $ext = $file->guessExtension();
                if (!$ext) {
                    $ext = $file->getClientOriginalExtension() ?? 'jpg';
                }
                $ext = strtolower($ext);
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'heic', 'heif', 'pdf'])) {
                    $ext = 'jpg';
                }
                $filename = Str::random(40) . '.' . $ext;
                Storage::disk('public')->put('attachments/' . $filename, $file->get());
                $attachmentPath = 'attachments/' . $filename;
            }
        }

        $datePrefix = Carbon::now()->format('ymd');
        $lastTicket = Ticket::where('ticket_number', 'like', 'HM' . $datePrefix . '%')
            ->orderBy('id', 'desc')->first();
        $sequence = 1;
        if ($lastTicket) {
            $lastSequence = (int) substr($lastTicket->ticket_number, -4);
            $sequence = $lastSequence + 1;
        }
        $ticketNumber = 'HM' . $datePrefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        $ticket = Ticket::create([
            'ticket_number'  => $ticketNumber,
            'type'           => $request->type,
            'category_id'    => $request->category_id,
            'room_id'        => $request->room_id,
            'is_anonymous'   => false,
            'reporter_name'  => $request->reporter_name,
            'reporter_phone' => $request->reporter_phone,
            'title'          => $request->title,
            'description'    => $request->description,
            'attachment_path'=> $attachmentPath,
            'status'         => 'NEW',
        ]);

        $ticket->histories()->create([
            'user_id'    => auth()->id(),
            'old_status' => '-',
            'new_status' => 'NEW',
            'notes'      => 'Pengaduan dibuat oleh Admin (' . auth()->user()->nama . ') atas nama ' . $request->reporter_name,
        ]);

        return redirect()->route('admin.tickets.show', $ticket->id)
            ->with('success', 'Pengaduan #' . $ticket->ticket_number . ' berhasil dibuat.');
    }

    public function edit(string $id) {}

    public function destroy(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Pengaduan #' . $ticket->ticket_number . ' berhasil dihapus.');
    }
}
