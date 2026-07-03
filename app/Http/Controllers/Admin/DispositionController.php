<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Disposition;
use App\Models\DispositionActivity;

class DispositionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'unit_id' => 'required|exists:units,id',
            'head_user_id' => 'required|exists:users,id',
            'priority' => 'required|in:rendah,normal,tinggi,sangat_tinggi',
            'deadline' => 'required|date',
            'instruction' => 'required|string'
        ]);

        $ticket = Ticket::findOrFail($request->ticket_id);

        if ($ticket->disposition) {
            return back()->with('error', 'Disposisi untuk pengaduan ini sudah ada.');
        }

        $disposition = Disposition::create([
            'ticket_id' => $ticket->id,
            'unit_id' => $request->unit_id,
            'head_user_id' => $request->head_user_id,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'instruction' => $request->instruction,
            'status' => 'menunggu_respon',
            'created_by' => auth()->id() ?? 2, // Default to admin for demo
        ]);

        DispositionActivity::create([
            'disposition_id' => $disposition->id,
            'user_id' => auth()->id() ?? 2, // Admin User
            'activity' => 'Disposisi Dibuat',
            'description' => 'Disposisi dibuat dan diteruskan ke Kepala Unit',
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'Disposisi berhasil dibuat.');
    }
}
