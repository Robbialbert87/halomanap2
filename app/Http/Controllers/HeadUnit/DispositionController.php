<?php

namespace App\Http\Controllers\HeadUnit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkflowHistory;

class DispositionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Cari workflow aktif yang ditugaskan ke user ini
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
            'fromUser'
        ])->where('uuid', $id)->firstOrFail();

        if ($workflow->to_user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke pengaduan ini.');
        }

        return view('head_unit.dispositions.show', compact('workflow', 'user'));
    }
}

