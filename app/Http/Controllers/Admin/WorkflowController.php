<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\WorkflowHistory;
use App\Services\WorkflowService;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{
    public function __construct(
        private readonly WorkflowService $workflowService,
    ) {}

    public function disposisi(Request $request)
    {
        $request->validate([
            'ticket_id'  => 'required|exists:tickets,id',
            'jabatan_id' => 'required|exists:jabatans,id',
            'komentar'   => 'nullable|string|max:1000',
            'due_at'     => 'nullable|date|after:now',
        ]);

        $ticket = Ticket::findOrFail($request->ticket_id);

        try {
            $this->workflowService->disposisi(
                ticket: $ticket,
                toJabatanId: $request->jabatan_id,
                komentar: $request->komentar ?? '',
                dueAt: $request->due_at ? now()->parse($request->due_at) : null,
            );
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.tickets.show', $ticket->id)
            ->with('success', 'Pengaduan berhasil didisposisikan.');
    }

    public function eskalasi(Request $request, WorkflowHistory $history)
    {
        $request->validate([
            'komentar'       => 'nullable|string|max:1000',
            'target_user_id' => 'required|exists:users,id',
        ]);

        if ($history->to_user_id !== auth()->id()) {
            return back()->with('error', 'Anda bukan pemegang aktif pengaduan ini.');
        }

        try {
            $this->workflowService->eskalasi(
                $history,
                $request->target_user_id,
                $request->komentar ?? ''
            );
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Pengaduan berhasil dieskalasi.');
    }

    public function tanganiSendiri(Request $request, WorkflowHistory $history)
    {
        $request->validate(['komentar' => 'nullable|string|max:1000']);

        if ($history->to_user_id !== auth()->id()) {
            return back()->with('error', 'Anda bukan pemegang aktif pengaduan ini.');
        }

        $this->workflowService->tanganiSendiri($history, $request->komentar ?? '');

        return back()->with('success', 'Pengaduan sedang dalam penanganan Anda.');
    }

    public function selesai(Request $request, WorkflowHistory $history)
    {
        $request->validate(['komentar' => 'nullable|string|max:1000']);

        if ($history->to_user_id !== auth()->id()) {
            return back()->with('error', 'Anda bukan pemegang aktif pengaduan ini.');
        }

        $this->workflowService->selesai($history, $request->komentar ?? '');

        return back()->with('success', 'Pengaduan ditandai selesai dan menunggu verifikasi admin.');
    }

    public function tutup(Request $request, WorkflowHistory $history)
    {
        $request->validate(['komentar' => 'nullable|string|max:1000']);

        $this->workflowService->tutup($history, $request->komentar ?? '');

        return redirect()->route('admin.tickets.show', $history->ticket_id)
            ->with('success', 'Pengaduan berhasil ditutup.');
    }
}
