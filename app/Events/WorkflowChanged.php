<?php

namespace App\Events;

use App\Models\WorkflowHistory;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkflowChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param WorkflowHistory $history  Record workflow baru yang baru saja dibuat/diubah.
     * @param string          $jenis    Jenis notifikasi: disposisi_baru, eskalasi, pengaduan_selesai, dll.
     */
    public function __construct(
        public readonly WorkflowHistory $history,
        public readonly string $jenis,
    ) {}
}
