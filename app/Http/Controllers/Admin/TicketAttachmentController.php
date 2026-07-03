<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketAttachmentController extends Controller
{
    public function store(Request $request, string $ticketId)
    {
        $request->validate([
            'attachment_type' => 'required|string',
            'description'     => 'nullable|string',
            'file'            => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $ticket = Ticket::findOrFail($ticketId);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $fileSize = $file->getSize();

        // Generate a unique path
        $path = $file->storeAs('ticket_attachments', Str::uuid() . '_' . $originalName, 'public');

        $ticket->attachments()->create([
            'user_id'         => auth()->id(), // null if not authenticated
            'attachment_type' => $request->attachment_type,
            'description'     => $request->description,
            'file_name'       => $originalName,
            'file_path'       => $path,
            'mime_type'       => $mimeType,
            'file_size'       => $fileSize,
        ]);

        return back()->with('success', 'Lampiran berhasil ditambahkan.');
    }

    public function download(string $ticketId, string $attachmentId)
    {
        $attachment = TicketAttachment::where('ticket_id', $ticketId)->findOrFail($attachmentId);
        
        $path = storage_path('app/public/' . $attachment->file_path);
        
        if (!file_exists($path)) {
            abort(404, 'File not found');
        }

        return response()->download($path, $attachment->file_name);
    }
}
