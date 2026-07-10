<?php

namespace App\Services;

use App\Events\WorkflowChanged;
use App\Models\AppNotification;
use App\Models\AuditTrail;
use App\Models\Jabatan;
use App\Models\Ticket;
use App\Models\TicketHistory;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkflowHistory;
use Illuminate\Support\Facades\DB;

class WorkflowService
{
    /**
     * Admin mendisposisikan tiket ke Jabatan Tujuan.
     * Sistem otomatis mencari User aktif dengan jabatan yang dipilih.
     */
    public function disposisi(Ticket $ticket, int $toJabatanId, string $komentar = '', $dueAt = null): WorkflowHistory
    {
        $actor = auth()->user();

        $jabatan = Jabatan::find($toJabatanId);
        if (!$jabatan) {
            throw new \RuntimeException("Jabatan ID {$toJabatanId} tidak ditemukan.");
        }

        $toUser = User::where('jabatan_id', $toJabatanId)
            ->where('status', 'active')
            ->first();

        if (!$toUser) {
            throw new \RuntimeException("Tidak ditemukan pengguna aktif dengan jabatan {$jabatan->nama}. Harap daftarkan user terlebih dahulu.");
        }

        $toUnit = $toUser->unit;
        if (!$toUnit) {
            throw new \RuntimeException("Pengguna {$toUser->nama} dengan jabatan {$jabatan->nama} belum memiliki unit. Harap atur unit pengguna terlebih dahulu.");
        }

        return DB::transaction(function () use ($ticket, $actor, $jabatan, $toUser, $toUnit, $komentar, $dueAt) {
            $existing = WorkflowHistory::where('ticket_id', $ticket->id)
                ->whereIn('status', ['menunggu_respon', 'dalam_penanganan'])
                ->lockForUpdate()
                ->first();

            if ($existing) {
                throw new \RuntimeException('Tiket ini masih memiliki disposisi yang aktif. Selesaikan disposisi yang ada terlebih dahulu.');
            }

            $this->deactivateActiveWorkflows($ticket->id);

            $history = WorkflowHistory::create([
                'ticket_id'       => $ticket->id,
                'from_user_id'    => $actor?->id,
                'to_user_id'      => $toUser?->id,
                'from_jabatan_id' => $actor?->jabatan_id,
                'to_jabatan_id'   => $jabatan->id,
                'from_unit_id'    => $actor?->unit_id,
                'to_unit_id'      => $toUnit->id,
                'action'          => 'disposisi',
                'komentar'        => $komentar,
                'status'          => 'menunggu_respon',
                'due_at'          => $dueAt,
            ]);

            $ticket->update(['status' => 'Diproses']);

            TicketHistory::create([
                'ticket_id'  => $ticket->id,
                'user_id'    => $actor?->id,
                'old_status' => 'TERVERIFIKASI',
                'new_status' => 'Diproses',
                'notes'      => 'Pengaduan didisposisikan ke jabatan: ' . ($jabatan->nama ?? '-') . ' (' . ($toUnit->nama ?? '-') . ')' . ($komentar ? ' — ' . $komentar : ''),
            ]);

            AuditTrail::log('disposisi', Ticket::class, $ticket->id, [
                'to_jabatan' => $jabatan->id,
                'to_user'    => $toUser?->id,
                'to_unit'    => $toUnit->id,
            ]);

            AppNotification::create([
                'user_id' => $toUser->id,
                'type'    => 'disposisi_baru',
                'title'   => 'Disposisi Pengaduan Baru',
                'message' => $komentar ?: 'Anda menerima disposisi pengaduan baru.',
                'data'    => [
                    'ticket_id'      => $ticket->id,
                    'ticket_number'  => $ticket->ticket_number,
                    'workflow_uuid'  => $history->uuid,
                    'url'            => route('admin.tickets.show', $ticket->id),
                ],
            ]);

            event(new WorkflowChanged($history, 'disposisi_baru'));

            return $history;
        });
    }

    /**
     * Eskalasi ke user tertentu.
     *
     * @param WorkflowHistory $currentHistory
     * @param int             $targetUserId   ID User tujuan eskalasi
     * @param string          $komentar
     * @return WorkflowHistory
     */
    public function eskalasi(WorkflowHistory $currentHistory, int $targetUserId, string $komentar = ''): WorkflowHistory
    {
        $actor = auth()->user();

        $toUser = User::with('jabatan')->find($targetUserId);
        if (!$toUser || $toUser->status !== 'active') {
            throw new \RuntimeException('User tujuan tidak ditemukan atau tidak aktif.');
        }

        $targetJabatan = $toUser->jabatan;
        if (!$targetJabatan) {
            throw new \RuntimeException('User tujuan tidak memiliki jabatan.');
        }

        return DB::transaction(function () use ($currentHistory, $actor, $targetJabatan, $toUser, $komentar) {
            $fresh = WorkflowHistory::lockForUpdate()->find($currentHistory->id);
            if (!$fresh || !in_array($fresh->status, ['menunggu_respon', 'dalam_penanganan'])) {
                throw new \RuntimeException('Workflow ini sudah tidak aktif atau telah diubah.');
            }

            $currentHistory->update(['status' => 'eskalasi', 'completed_at' => now()]);

            $newHistory = WorkflowHistory::create([
                'ticket_id'       => $currentHistory->ticket_id,
                'from_user_id'    => $actor?->id,
                'to_user_id'      => $toUser?->id,
                'from_jabatan_id' => $currentHistory->to_jabatan_id,
                'to_jabatan_id'   => $targetJabatan->id,
                'from_unit_id'    => $currentHistory->to_unit_id,
                'to_unit_id'      => $currentHistory->to_unit_id,
                'action'          => 'eskalasi',
                'komentar'        => $komentar,
                'status'          => 'menunggu_respon',
                'due_at'          => $currentHistory->due_at,
            ]);

            AuditTrail::log('eskalasi', Ticket::class, $currentHistory->ticket_id, [
                'from_jabatan' => $currentHistory->to_jabatan_id,
                'to_jabatan'   => $targetJabatan->id,
                'to_user'      => $toUser?->id,
            ]);

            AppNotification::create([
                'user_id' => $toUser->id,
                'type'    => 'eskalasi',
                'title'   => 'Eskalasi Pengaduan',
                'message' => $komentar ?: 'Ada pengaduan dieskalasi kepada Anda.',
                'data'    => [
                    'ticket_id'      => $currentHistory->ticket_id,
                    'ticket_number'  => $currentHistory->ticket->ticket_number,
                    'workflow_uuid'  => $newHistory->uuid,
                    'url'            => route('admin.tickets.show', $currentHistory->ticket_id),
                ],
            ]);

            event(new WorkflowChanged($newHistory, 'eskalasi'));

            return $newHistory;
        });
    }

    public function tanganiSendiri(WorkflowHistory $history, string $komentar = ''): WorkflowHistory
    {
        $history->update([
            'status'   => 'dalam_penanganan',
            'komentar' => $komentar,
        ]);

        AuditTrail::log('tangani_sendiri', Ticket::class, $history->ticket_id);
        event(new WorkflowChanged($history, 'dalam_penanganan'));

        return $history;
    }

    public function selesai(WorkflowHistory $history, string $komentar = ''): WorkflowHistory
    {
        $ticket = $history->ticket;

        DB::transaction(function () use ($history, $ticket, $komentar) {
            $history->update([
                'status'       => 'selesai',
                'completed_at' => now(),
            ]);

            $actor = auth()->user();
            $newHistory = WorkflowHistory::create([
                'ticket_id'       => $history->ticket_id,
                'from_user_id'    => $actor?->id,
                'from_jabatan_id' => $actor?->jabatan_id,
                'from_unit_id'    => $actor?->unit_id,
                'action'          => 'selesai',
                'komentar'        => $komentar,
                'status'          => 'menunggu_verifikasi',
                'completed_at'    => now(),
                'due_at'          => $history->due_at,
            ]);

            $ticket->update(['status' => 'Menunggu Verifikasi']);

            TicketHistory::create([
                'ticket_id'  => $ticket->id,
                'user_id'    => auth()->id(),
                'old_status' => 'Diproses',
                'new_status' => 'Menunggu Verifikasi',
                'notes'      => 'Penanganan selesai oleh ' . (auth()->user()?->nama ?? 'Petugas') . ($komentar ? ': ' . $komentar : '.'),
            ]);

            AuditTrail::log('selesai', Ticket::class, $ticket->id);

            $adminUsers = User::role(['Super Admin', 'Admin Pengaduan'])->get();
            foreach ($adminUsers as $admin) {
                AppNotification::create([
                    'user_id' => $admin->id,
                    'type'    => 'pengaduan_selesai',
                    'title'   => 'Pengaduan Selesai Diproses',
                    'message' => $komentar ?: 'Pengaduan telah selesai diproses dan menunggu verifikasi.',
                    'data'    => [
                        'ticket_id'      => $ticket->id,
                        'ticket_number'  => $ticket->ticket_number,
                        'url'            => route('admin.tickets.show', $ticket->id),
                    ],
                ]);
            }

            event(new WorkflowChanged($newHistory, 'pengaduan_selesai'));
        });

        return $history->fresh();
    }

    public function tutup(WorkflowHistory $history, string $komentar = ''): WorkflowHistory
    {
        $ticket = $history->ticket;

        DB::transaction(function () use ($history, $ticket, $komentar) {
            $history->update([
                'status'       => 'ditutup',
                'completed_at' => now(),
            ]);

            $actor = auth()->user();
            $newHistory = WorkflowHistory::create([
                'ticket_id'       => $history->ticket_id,
                'from_user_id'    => $actor?->id,
                'from_jabatan_id' => $actor?->jabatan_id,
                'from_unit_id'    => $actor?->unit_id,
                'action'          => 'tutup',
                'komentar'        => $komentar,
                'status'          => 'ditutup',
                'completed_at'    => now(),
            ]);

            $ticket->update(['status' => 'Selesai', 'notification_seen_at' => null]);

            TicketHistory::create([
                'ticket_id'  => $ticket->id,
                'user_id'    => auth()->id(),
                'old_status' => 'Menunggu Verifikasi',
                'new_status' => 'Selesai',
                'notes'      => 'Pengaduan diverifikasi dan ditutup oleh Admin.' . ($komentar ? ' Catatan: ' . $komentar : ''),
            ]);

            AuditTrail::log('tutup_pengaduan', Ticket::class, $ticket->id);
            event(new WorkflowChanged($newHistory, 'pengaduan_ditutup'));
        });

        return $history->fresh();
    }

    public function getActiveWorkflow(int $ticketId): ?WorkflowHistory
    {
        return WorkflowHistory::where('ticket_id', $ticketId)
            ->whereNotIn('status', ['eskalasi', 'selesai', 'ditutup', 'menunggu_verifikasi'])
            ->latest()
            ->first();
    }

    public function getTimeline(int $ticketId)
    {
        return WorkflowHistory::with(['fromUser', 'toUser', 'fromJabatan', 'toJabatan', 'toUnit'])
            ->where('ticket_id', $ticketId)
            ->orderBy('created_at')
            ->get();
    }

    private function deactivateActiveWorkflows(int $ticketId): void
    {
        WorkflowHistory::where('ticket_id', $ticketId)
            ->whereNotIn('status', ['eskalasi', 'selesai', 'ditutup'])
            ->update(['status' => 'didisposisikan', 'completed_at' => now()]);
    }

    public function isActiveHolder(int $ticketId, int $userId): bool
    {
        return WorkflowHistory::where('ticket_id', $ticketId)
            ->where('to_user_id', $userId)
            ->whereNotIn('status', ['eskalasi', 'selesai', 'ditutup', 'menunggu_verifikasi'])
            ->exists();
    }
}
