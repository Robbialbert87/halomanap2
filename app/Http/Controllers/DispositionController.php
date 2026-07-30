<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\User;
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

        return view('dispositions.index', ['activeWorkflows' => $activeWorkflows, 'user' => $user]);
    }

    public function show(string $id)
    {
        $user = auth()->user();

        $workflow = WorkflowHistory::with([
            'ticket.histories.user',
            'ticket.comments.user',
            'ticket.attachments.user',
            'ticket.workflows.fromUser',
            'ticket.workflows.toUser',
            'ticket.workflows.toJabatan',
            'ticket.workflows.fromJabatan',
            'fromUser',
        ])->where('uuid', $id)->firstOrFail();

        abort_if($workflow->to_user_id !== $user->id, 403);

        AppNotification::where('user_id', $user->id)
            ->where('data->ticket_id', $workflow->ticket_id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $eskalasiKategori = match ($user->getRoleGroup()) {
            'kabid' => ['Direktur'],
            'kasi' => ['Kabid', 'Kabag'],
            'kepala_unit', 'head_unit' => ['Kasi', 'Kasubbag'],
            default => ['Direktur'],
        };

        $eskalasiUsers = User::with('jabatan', 'unit')
            ->whereIn('jabatan_id', fn ($q) => $q->select('id')->from('jabatans')
                ->whereIn('kategori_jabatan', $eskalasiKategori)->where('status', 'active'))
            ->where('status', 'active')
            ->where('id', '!=', $user->id)
            ->orderBy('jabatan_id')
            ->get();

        return view('dispositions.show', ['workflow' => $workflow, 'user' => $user, 'eskalasiUsers' => $eskalasiUsers]);
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

        $request->validate([
            'komentar' => 'nullable|string|max:1000',
            'target_user_id' => 'required|exists:users,id',
        ]);

        try {
            $this->workflowService->eskalasi($history, $request->target_user_id, $request->komentar ?? '');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Pengaduan berhasil dieskalasi.');
    }

    public function handleSelf(Request $request, WorkflowHistory $history)
    {
        return $this->workflowService->handleSelf($history, $request->komentar ?? '');
    }

    public function accept(Request $request, WorkflowHistory $history)
    {
        return $this->workflowService->accept($history);
    }

    public function assign(Request $request, WorkflowHistory $history)
    {
        return $this->workflowService->assign($history, $request->target_user_id);
    }
}
