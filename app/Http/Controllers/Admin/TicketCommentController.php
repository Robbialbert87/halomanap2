<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{
    public function store(Request $request, string $ticketId)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $ticket = Ticket::findOrFail($ticketId);

        $ticket->comments()->create([
            'user_id' => auth()->id(), // null if not authenticated
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Komentar internal berhasil ditambahkan.');
    }
}
