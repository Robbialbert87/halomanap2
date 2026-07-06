<?php

namespace App\Services;

use App\Events\WorkflowChanged;
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
     * Admin mendisposisikan tiket ke Unit Tujuan.
     * Sistem otomatis mencari User aktif dengan Unit yang dipilih
     * dan Kategori Jabatan = 'Kepala Unit'.
     */
    public function disposisi(Ticket $ticket, int $toUnitId, string $komentar = '', $dueAt = null): WorkflowHistory
    {
        $actor = auth()->user();

        $unit = Unit::find($toUnitId);
        if (!$unit) {
            throw new \RuntimeException("Unit ID {$toUnitId} tidak ditemukan.");
        }

        // Cari Kepala Unit di unit tujuan
        $kepalaUnitJabatan = Jabatan::where('kategori_jabatan', 'Kepala Unit')->first();
        if (!$kepalaUnitJabatan) {
            throw new \RuntimeException("Master Jabatan dengan kategori 'Kepala Unit' belum tersedia. Harap buat jabatan dengan kategori Kepala Unit terlebih dahulu.");
        }

        $toUser = User::where('unit_id', $toUnitId)
            ->where('jabatan_id', $kepalaUnitJabatan->id)
            ->where('status', 'active')
            ->first();

        if (!$toUser) {
            throw new \RuntimeException("Tidak ditemukan user dengan jabatan {$kepalaUnitJabatan->nama} di unit {$unit->nama}. Harap daftarkan user terlebih dahulu.");
        }

        return DB::transaction(function () use ($ticket, $actor, $toUnitId, $kepalaUnitJabatan, $toUser, $komentar, $dueAt) {
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
                'to_jabatan_id'   => $kepalaUnitJabatan->id,
                'from_unit_id'    => $actor?->unit_id,
                'to_unit_id'      => $toUnitId,
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
                'notes'      => 'Pengaduan didisposisikan ke unit: ' . ($unit->nama ?? '-') . ($komentar ? ' — ' . $komentar : ''),
            ]);

            AuditTrail::log('disposisi', Ticket::class, $ticket->id, [
                'to_unit'    => $toUnitId,
                'to_user'    => $toUser?->id,
                'to_jabatan' => $kepalaUnitJabatan->id,
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
        $history->update([
            'status'       => 'menunggu_verifikasi',
            'action'       => 'selesai',
            'komentar'     => $komentar,
            'completed_at' => now(),
        ]);

        $history->ticket->update(['status' => 'Menunggu Verifikasi']);

        TicketHistory::create([
            'ticket_id'  => $history->ticket_id,
            'user_id'    => auth()->id(),
            'old_status' => 'Diproses',
            'new_status' => 'Menunggu Verifikasi',
            'notes'      => 'Penanganan selesai oleh ' . (auth()->user()?->nama ?? 'Petugas') . ($komentar ? ': ' . $komentar : '.'),
        ]);

        AuditTrail::log('selesai', Ticket::class, $history->ticket_id);
        event(new WorkflowChanged($history, 'pengaduan_selesai'));

        return $history;
    }

    public function tutup(WorkflowHistory $history, string $komentar = ''): WorkflowHistory
    {
        $history->update([
            'status'   => 'ditutup',
            'action'   => 'tutup',
            'komentar' => $komentar,
        ]);

        $history->ticket->update(['status' => 'Selesai']);

        TicketHistory::create([
            'ticket_id'  => $history->ticket_id,
            'user_id'    => auth()->id(),
            'old_status' => 'Menunggu Verifikasi',
            'new_status' => 'Selesai',
            'notes'      => 'Pengaduan diverifikasi dan ditutup oleh Admin.' . ($komentar ? ' Catatan: ' . $komentar : ''),
        ]);

        AuditTrail::log('tutup_pengaduan', Ticket::class, $history->ticket_id);
        event(new WorkflowChanged($history, 'pengaduan_ditutup'));

        return $history;
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
