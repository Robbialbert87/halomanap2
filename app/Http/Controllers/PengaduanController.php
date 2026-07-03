<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Room;
use App\Models\ReportCategory;
use App\Models\Ticket;
use Carbon\Carbon;

class PengaduanController extends Controller
{
    public function create(Request $request)
    {
        $units      = Unit::where('is_public', true)->orderBy('nama')->get();
        $rooms      = Room::orderBy('name')->get()->groupBy('unit_id');
        $categories = ReportCategory::orderBy('name')->get();

        $type = in_array($request->query('type'), ['Pengaduan', 'Saran', 'Apresiasi', 'Informasi'])
            ? $request->query('type')
            : 'Pengaduan';

        return view('pengaduan.create', compact('units', 'rooms', 'categories', 'type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required',
            'room_id' => 'required',
            'category_id' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'is_anonymous' => 'nullable|boolean',
        ]);

        $isAnonymous = $request->has('is_anonymous');
        
        if (!$isAnonymous) {
            $request->validate([
                'reporter_name' => 'required|string|max:255',
                'reporter_phone' => 'required|string|max:20',
                'reporter_email' => 'nullable|email|max:255',
            ]);
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $file = $request->file('attachment');
            if ($file->getRealPath() !== false && $file->getRealPath() !== '') {
                $attachmentPath = $file->store('attachments', 'public');
            }
        }

        // Generate Ticket Number HM + YYMMDD + XXXX
        $datePrefix = Carbon::now()->format('ymd');
        $lastTicket = Ticket::where('ticket_number', 'like', 'HM' . $datePrefix . '%')
            ->orderBy('id', 'desc')
            ->first();
            
        $sequence = 1;
        if ($lastTicket) {
            $lastSequence = (int) substr($lastTicket->ticket_number, -4);
            $sequence = $lastSequence + 1;
        }
        
        $ticketNumber = 'HM' . $datePrefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'type' => in_array($request->input('type'), ['Pengaduan', 'Saran', 'Apresiasi', 'Informasi']) ? $request->input('type') : 'Pengaduan',
            'category_id' => $request->category_id,
            'room_id' => $request->room_id,
            'is_anonymous' => $isAnonymous,
            'reporter_name' => $isAnonymous ? null : $request->reporter_name,
            'reporter_phone' => $isAnonymous ? null : $request->reporter_phone,
            'title' => $request->title,
            'description' => $request->description,
            'attachment_path' => $attachmentPath,
            'status' => 'NEW'
        ]);

        return redirect()->route('pengaduan.success', ['ticket_number' => $ticket->ticket_number]);
    }

    public function success($ticket_number)
    {
        $ticket = Ticket::where('ticket_number', $ticket_number)->firstOrFail();
        return view('pengaduan.success', compact('ticket'));
    }

    public function track(Request $request)
    {
        $ticket = null;
        $notFound = false;

        if ($request->filled('ticket_number')) {
            $ticket = Ticket::with(['room', 'category'])
                ->where('ticket_number', strtoupper(trim($request->ticket_number)))
                ->first();

            if (!$ticket) {
                $notFound = true;
            }
        }

        return view('pengaduan.track', compact('ticket', 'notFound'));
    }
}
