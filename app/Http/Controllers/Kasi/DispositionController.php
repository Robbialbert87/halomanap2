<?php

namespace App\Http\Controllers\Kasi;

use App\Http\Controllers\Controller;
use App\Models\WorkflowHistory;
use App\Services\WorkflowService;
use Illuminate\Http\Request;

class DispositionController extends Controller
{
    public function __construct(
        private readonly WorkflowService $workflowService,
    ) {}

    public function index(Request $request)
    {
        $user = auth()->user();

        $activeWorkflows = WorkflowHistory::with(['ticket.room.unit', 'ticket.category', 'fromUser'])
            ->where('to_user_id', $user->id)
            ->whereNotIn('status', ['eskalasi', 'selesai', 'ditutup', 'menunggu_verifikasi'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('kasi.dispositions.index', compact('activeWorkflows', 'user'));
    }

    public function show(string $id)
    {
        $user = auth()->user();

        $workflow = WorkflowHistory::with([
            'ticket.histories.user',
            'ticket.comments.user',
            'ticket.attachments.user',
            'fromUser'
        ])->where('uuid', $id)->firstOrFail();

        if ($workflow->to_user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke pengaduan ini.');
        }

        return view('kasi.dispositions.show', compact('workflow', 'user'));
    }

    public function selesai(Request $request, WorkflowHistory $history)
    {
        if ($history->to_user_id !== auth()->id()) {
            return back()->with('error', 'Anda bukan pemegang aktif pengaduan ini.');
        }

        $request->validate(['komentar' => 'nullable|string|max:1000']);
        $this->workflowService->selesai($history, $request->komentar ?? '');

        return back()->with('success', 'Pengaduan ditandai selesai dan menunggu verifikasi admin.');
    }

    public function eskalasi(Request $request, WorkflowHistory $history)
    {
        if ($history->to_user_id !== auth()->id()) {
            return back()->with('error', 'Anda bukan pemegang aktif pengaduan ini.');
        }

        $request->validate(['komentar' => 'nullable|string|max:1000']);

        try {
            $this->workflowService->eskalasi($history, $request->komentar ?? '');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Pengaduan berhasil dieskalasi ke jabatan atasan.');
    }
}
