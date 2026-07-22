<?php

namespace App\Http\Controllers\HeadUnit;

use App\Http\Controllers\Controller;
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

        return view('head_unit.dispositions.index', compact('activeWorkflows', 'user'));
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

        if ($workflow->to_user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke pengaduan ini.');
        }

        // Mark AppNotifications as read for this user & ticket
        AppNotification::where('user_id', $user->id)
            ->where('data->ticket_id', $workflow->ticket_id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $eskalasiUsers = User::with('jabatan', 'unit')
            ->whereIn('jabatan_id', function ($q) {
                $q->select('id')->from('jabatans')
                    ->whereIn('kategori_jabatan', ['Kasi', 'Kasubbag'])
                    ->where('status', 'active');
            })
            ->where('status', 'active')
            ->where('id', '!=', $user->id)
            ->orderBy('jabatan_id')
            ->get();

        return view('head_unit.dispositions.show', compact('workflow', 'user', 'eskalasiUsers'));
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
}
