<?php

namespace App\Services;

use App\Events\WorkflowChanged;
use App\Models\AuditTrail;
use App\Models\Ticket;
use App\Models\TicketHistory;
use App\Models\User;
use App\Models\WorkflowHistory;
use Illuminate\Support\Facades\DB;

class WorkflowService
{
    /**
     * Admin mendisposisikan tiket ke Kepala Unit (is_workflow_start = true).
     * Ini adalah entry point dari semua workflow.
     *
     * @param Ticket $ticket
     * @param int    $toUnitId    Unit tujuan disposisi
     * @param string $komentar
     * @param int|null $dueAt    SLA deadline
     * @return WorkflowHistory
     */
    public function disposisi(Ticket $ticket, int $toUnitId, string $komentar = '', $dueAt = null): WorkflowHistory
    {
        $actor = auth()->user();

        $unit = \App\Models\Unit::find($toUnitId);
        if (!$unit) {
            throw new \RuntimeException("Unit ID {$toUnitId} tidak ditemukan.");
        }

        if (!$unit->entry_jabatan_id) {
            throw new \RuntimeException("Unit {$unit->nama} tidak memiliki konfigurasi Jabatan Kepala (entry_jabatan_id). Mohon edit Master Unit terlebih dahulu.");
        }

        $entryJabatan = \App\Models\Jabatan::find($unit->entry_jabatan_id);
        if (!$entryJabatan) {
            throw new \RuntimeException("Master Jabatan untuk entry point tidak ditemukan.");
        }

        // Temukan user aktif di unit dan jabatan tersebut
        $toUser = User::where('unit_id', $toUnitId)
            ->where('jabatan_id', $entryJabatan->id)
            ->where('status', 'active')
            ->first();

        return DB::transaction(function () use ($ticket, $actor, $toUnitId, $entryJabatan, $toUser, $komentar, $dueAt) {
            // Tutup workflow aktif sebelumnya jika ada
            $this->deactivateActiveWorkflows($ticket->id);

            $history = WorkflowHistory::create([
                'ticket_id'        => $ticket->id,
                'from_user_id'     => $actor?->id,
                'to_user_id'       => $toUser?->id,
                'from_jabatan_id'  => $actor?->jabatan_id,
                'to_jabatan_id'    => $entryJabatan->id,
                'from_unit_id'     => $actor?->unit_id,
                'to_unit_id'       => $toUnitId,
                'workflow_level'   => $entryJabatan->level,
                'action'           => 'disposisi',
                'komentar'         => $komentar,
                'status'           => 'menunggu_respon',
                'due_at'           => $dueAt,
            ]);

            // Update status tiket
            $ticket->update(['status' => 'Diproses']);

            // Catat ke Riwayat Status
            TicketHistory::create([
                'ticket_id'  => $ticket->id,
                'user_id'    => $actor?->id,
                'old_status' => 'TERVERIFIKASI',
                'new_status' => 'Diproses',
                'notes'      => 'Pengaduan didisposisikan ke unit: ' . ($toUser?->unit?->nama ?? '-') . ($komentar ? ' — ' . $komentar : ''),
            ]);

            // Audit trail
            AuditTrail::log('disposisi', Ticket::class, $ticket->id, [
                'to_unit'    => $toUnitId,
                'to_user'    => $toUser?->id,
                'to_jabatan' => $entryJabatan->id,
            ]);

            // Dispatch event untuk log / real-time (jika ada)
            event(new WorkflowChanged($history, 'disposisi_baru'));

            // Kirim notifikasi WhatsApp ke penerima jika ada nomor HP
            if ($toUser && $toUser->phone_number) {
                $pesan = "Halo *{$toUser->nama}*,\n\n";
                $pesan .= "Ada pengaduan baru yang didisposisikan ke unit Anda dengan nomor tiket: *{$ticket->ticket_number}*.\n\n";
                if ($komentar) {
                    $pesan .= "Catatan Admin: {$komentar}\n\n";
                }
                $pesan .= "Silakan klik link berikut untuk melihat detail dan menindaklanjuti:\n";
                $pesan .= route('kepala-unit.dispositions.show', $history->id); // Sesuaikan rute jika nanti diubah

                \App\Jobs\SendWhatsAppNotification::dispatch($toUser->phone_number, $pesan);
            }

            return $history;
        });
    }

    /**
     * Eskalasi: sistem otomatis mencari parent jabatan, mencari user aktif, lalu buat history baru.
     * TIDAK ADA IF berdasarkan nama jabatan.
     *
     * @param WorkflowHistory $currentHistory
     * @param string          $komentar
     * @return WorkflowHistory
     */
    public function eskalasi(WorkflowHistory $currentHistory, string $komentar = ''): WorkflowHistory
    {
        $actor = auth()->user();

        // Cari jabatan saat ini dari master
        $currentJabatan = \App\Models\Jabatan::find($currentHistory->to_jabatan_id);
        
        if (! $currentJabatan) {
            throw new \RuntimeException("Master Jabatan tidak ditemukan.");
        }

        // Jika tidak memiliki parent_id, berarti sudah level puncak
        if (! $currentJabatan->parent_id) {
            throw new \RuntimeException("Pengaduan telah mencapai puncak hierarki. Tidak dapat dieskalasi lebih lanjut.");
        }

        $parentJabatan = \App\Models\Jabatan::find($currentJabatan->parent_id);

        if (! $parentJabatan) {
            throw new \RuntimeException("Konfigurasi atasan (parent) untuk jabatan ini tidak ditemukan di Master Jabatan.");
        }

        // Temukan user aktif di jabatan atasan dalam unit yang sama
        $toUser = User::where('unit_id', $currentHistory->to_unit_id)
            ->where('jabatan_id', $parentJabatan->id)
            ->where('status', 'active')
            ->first();

        // Jika tidak ditemukan di unit yang sama (misal atasan berada di unit global), cari di unit manapun
        if (! $toUser) {
            $toUser = User::where('jabatan_id', $parentJabatan->id)
                ->where('status', 'active')
                ->first();
        }

        // Level eskalasi diambil dari level jabatan atasan
        $workflowLevel = $parentJabatan->level;

        return DB::transaction(function () use ($currentHistory, $actor, $parentJabatan, $toUser, $workflowLevel, $komentar) {
            // Tandai history sebelumnya sebagai sudah dieskalasi
            $currentHistory->update(['status' => 'eskalasi', 'completed_at' => now()]);

            $newHistory = WorkflowHistory::create([
                'ticket_id'        => $currentHistory->ticket_id,
                'from_user_id'     => $actor?->id,
                'to_user_id'       => $toUser?->id,
                'from_jabatan_id'  => $currentHistory->to_jabatan_id,
                'to_jabatan_id'    => $parentJabatan->id,
                'from_unit_id'     => $currentHistory->to_unit_id,
                'to_unit_id'       => $currentHistory->to_unit_id,
                'workflow_level'   => $workflowLevel,
                'action'           => 'eskalasi',
                'komentar'         => $komentar,
                'status'           => 'menunggu_respon',
                'due_at'           => $currentHistory->due_at, // carry forward SLA
            ]);

            // Audit trail
            AuditTrail::log('eskalasi', Ticket::class, $currentHistory->ticket_id, [
                'from_jabatan' => $currentHistory->to_jabatan_id,
                'to_jabatan'   => $parentJabatan->id,
                'to_user'      => $toUser?->id,
            ]);

            // Dispatch event
            event(new WorkflowChanged($newHistory, 'eskalasi'));

            // Kirim notifikasi WhatsApp ke atasan jika ada nomor HP
            if ($toUser && $toUser->phone_number) {
                $ticket = Ticket::find($currentHistory->ticket_id);
                $pesan = "Halo *{$toUser->nama}*,\n\n";
                $pesan .= "Terdapat eskalasi pengaduan dari bawahan Anda dengan nomor tiket: *{$ticket->ticket_number}*.\n\n";
                if ($komentar) {
                    $pesan .= "Catatan Eskalasi: {$komentar}\n\n";
                }
                $pesan .= "Silakan klik link berikut untuk melihat detail dan mengambil tindakan:\n";
                // Rute mungkin berbeda tergantung role, menggunakan generic route jika ada, atau URL frontend.
                // Untuk sementara, mari arahkan ke halaman utama backend.
                $pesan .= url('/admin'); 

                \App\Jobs\SendWhatsAppNotification::dispatch($toUser->phone_number, $pesan);
            }

            return $newHistory;
        });
    }

    /**
     * Pemegang saat ini memilih Tangani Sendiri — workflow berhenti di sini,
     * status berubah ke "dalam_penanganan".
     */
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

    /**
     * Tandai workflow ini selesai, lalu minta verifikasi admin.
     */
    public function selesai(WorkflowHistory $history, string $komentar = ''): WorkflowHistory
    {
        $history->update([
            'status'       => 'menunggu_verifikasi',
            'action'       => 'selesai',
            'komentar'     => $komentar,
            'completed_at' => now(),
        ]);

        $history->ticket->update(['status' => 'Menunggu Verifikasi']);

        // Catat ke Riwayat Status
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

    /**
     * Admin memverifikasi dan menutup pengaduan.
     */
    public function tutup(WorkflowHistory $history, string $komentar = ''): WorkflowHistory
    {
        $history->update([
            'status'   => 'ditutup',
            'action'   => 'tutup',
            'komentar' => $komentar,
        ]);

        $history->ticket->update(['status' => 'Selesai']);

        // Catat ke Riwayat Status
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

    /**
     * Ambil workflow aktif (yang belum selesai/ditutup) dari sebuah tiket.
     */
    public function getActiveWorkflow(int $ticketId): ?WorkflowHistory
    {
        return WorkflowHistory::where('ticket_id', $ticketId)
            ->whereNotIn('status', ['eskalasi', 'selesai', 'ditutup', 'menunggu_verifikasi'])
            ->latest()
            ->first();
    }

    /**
     * Ambil seluruh timeline workflow sebuah tiket.
     */
    public function getTimeline(int $ticketId)
    {
        return WorkflowHistory::with(['fromUser', 'toUser', 'fromJabatan', 'toJabatan', 'toUnit'])
            ->where('ticket_id', $ticketId)
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Nonaktifkan workflow lama sebelum disposisi baru dibuat.
     */
    private function deactivateActiveWorkflows(int $ticketId): void
    {
        WorkflowHistory::where('ticket_id', $ticketId)
            ->whereNotIn('status', ['eskalasi', 'selesai', 'ditutup'])
            ->update(['status' => 'didisposisikan', 'completed_at' => now()]);
    }

    /**
     * Cek apakah user tertentu adalah pemegang aktif sebuah tiket.
     */
    public function isActiveHolder(int $ticketId, int $userId): bool
    {
        return WorkflowHistory::where('ticket_id', $ticketId)
            ->where('to_user_id', $userId)
            ->whereNotIn('status', ['eskalasi', 'selesai', 'ditutup', 'menunggu_verifikasi'])
            ->exists();
    }
}
