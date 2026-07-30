<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $tickets = Ticket::with(['room.unit', 'category'])
            ->whereHas('room', fn ($q) => $q->where('unit_id', $user->unit_id))
            ->when(request('status'), fn ($q, $v) => $q->where('status', $v))
            ->when(request('start_date'), fn ($q, $v) => $q->whereDate('created_at', '>=', Carbon::parse($v)))
            ->when(request('end_date'), fn ($q, $v) => $q->whereDate('created_at', '<=', Carbon::parse($v)))
            ->latest()
            ->get();

        return view('laporan', ['user' => $user, 'tickets' => $tickets]);
    }
}
