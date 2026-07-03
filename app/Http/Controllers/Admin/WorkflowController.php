<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Unit;
use App\Models\WorkflowHistory;
use App\Services\WorkflowService;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{
    public function __construct(
        private readonly WorkflowService $workflowService,
    ) {}

    /**
     * Admin mendisposisikan tiket ke unit tertentu.
     */
    public function disposisi(Request $request)
    {
        $request->validate([
            'ticket_id'  => 'required|exists:tickets,id',
            'unit_id'    => 'required|exists:units,id',
            'komentar'   => 'nullable|string|max:1000',
            'due_at'     => 'nullable|date|after:now',
        ]);

        $ticket = Ticket::findOrFail($request->ticket_id);

        try {
            $this->workflowService->disposisi(
                ticket: $ticket,
                toUnitId: $request->unit_id,
                komentar: $request->komentar ?? '',
                dueAt: $request->due_at ? now()->parse($request->due_at) : null,
            );
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.tickets.show', $ticket->id)
            ->with('success', 'Pengaduan berhasil didisposisikan.');
    }

    /**
     * User mengeskalasi ke jabatan atasannya.
     */
    public function eskalasi(Request $request, WorkflowHistory $history)
    {
        $request->validate(['komentar' => 'nullable|string|max:1000']);

        // Pastikan yang mengeskalasi adalah pemegang aktif saat ini
        if ($history->to_user_id !== auth()->id()) {
            return back()->with('error', 'Anda bukan pemegang aktif pengaduan ini.');
        }

        try {
            $this->workflowService->eskalasi($history, $request->komentar ?? '');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Pengaduan berhasil dieskalasi ke jabatan atasan.');
    }

    /**
     * User memilih Tangani Sendiri.
     */
    public function tanganiSendiri(Request $request, WorkflowHistory $history)
    {
        $request->validate(['komentar' => 'nullable|string|max:1000']);

        if ($history->to_user_id !== auth()->id()) {
            return back()->with('error', 'Anda bukan pemegang aktif pengaduan ini.');
        }

        $this->workflowService->tanganiSendiri($history, $request->komentar ?? '');

        return back()->with('success', 'Pengaduan sedang dalam penanganan Anda.');
    }

    /**
     * User menyelesaikan pengaduan (minta verifikasi admin).
     */
    public function selesai(Request $request, WorkflowHistory $history)
    {
        $request->validate(['komentar' => 'nullable|string|max:1000']);

        if ($history->to_user_id !== auth()->id()) {
            return back()->with('error', 'Anda bukan pemegang aktif pengaduan ini.');
        }

        $this->workflowService->selesai($history, $request->komentar ?? '');

        return back()->with('success', 'Pengaduan ditandai selesai dan menunggu verifikasi admin.');
    }

    /**
     * Admin menutup pengaduan setelah verifikasi.
     */
    public function tutup(Request $request, WorkflowHistory $history)
    {
        $request->validate(['komentar' => 'nullable|string|max:1000']);

        $this->workflowService->tutup($history, $request->komentar ?? '');

        return redirect()->route('admin.tickets.show', $history->ticket_id)
            ->with('success', 'Pengaduan berhasil ditutup.');
    }
}
