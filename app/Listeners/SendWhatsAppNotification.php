<?php

namespace App\Listeners;

use App\Events\WorkflowChanged;
use App\Models\NotificationLog;
use App\Models\User;
use App\Services\RoleMenuService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class SendWhatsAppNotification
{
    public function handle(WorkflowChanged $event): void
    {
        $history = $event->history;
        $jenis   = $event->jenis;
        $ticket  = $history->ticket;

        if (!$ticket) {
            Log::channel('daily')->warning('[WhatsApp] Tiket tidak ditemukan untuk history ID: ' . $history->id);
            return;
        }

        // ─── 1. Kirim ke User Penerima (To User) ────────────────────────────
        if ($history->toUser && $history->toUser->phone_number) {
            $message = $this->buildMessage($jenis, $history, $ticket);
            $this->send($history->toUser, $message, $jenis, $history);
        }

        // ─── 2. Notifikasi Admin Pengaduan saat pengaduan menunggu verifikasi ─
        // Ketika Kepala Unit/Kasi/Kabid klik Selesai, admin perlu diingatkan
        // untuk memverifikasi dan menutup pengaduan.
        if ($jenis === 'pengaduan_selesai') {
            $admins = User::role('Admin Pengaduan')->whereNotNull('phone_number')->get();
            $adminMessage = $this->buildAdminVerificationMessage($history, $ticket);
            foreach ($admins as $admin) {
                $this->send($admin, $adminMessage, 'pengaduan_selesai', $history);
            }
        }
    }

    /**
     * Bangun isi pesan WA untuk penerima disposisi/eskalasi.
     */
    private function buildMessage(string $jenis, $history, $ticket): string
    {
        $toJabatan = $history->toJabatan?->nama ?? 'Anda';
        $toUnit    = $history->toUnit?->nama    ?? '-';
        $nomor     = $ticket->ticket_number     ?? '-';
        $judul     = $ticket->title             ?? '-';
        $url       = $this->getInboxUrl($history->toUser);

        return match ($jenis) {
            'disposisi_baru' => implode("\n", [
                "*HALO MANAP - Disposisi Baru*",
                "─────────────────────",
                "Yth. *{$toJabatan}*",
                "Unit: {$toUnit}",
                "",
                "Anda menerima disposisi pengaduan baru:",
                "📋 *No:* {$nomor}",
                "📝 *Judul:* {$judul}",
                "",
                "Silakan login untuk memproses pengaduan.",
                "🔗 {$url}",
                "─────────────────────",
                "_RSUD H. Abdul Manap Kota Jambi_",
            ]),
            'eskalasi' => implode("\n", [
                "*HALO MANAP - Eskalasi Pengaduan*",
                "─────────────────────",
                "Yth. *{$toJabatan}*",
                "Unit: {$toUnit}",
                "",
                "Pengaduan berikut dieskalasi kepada Anda:",
                "📋 *No:* {$nomor}",
                "📝 *Judul:* {$judul}",
                "⚠️ Mohon segera ditindaklanjuti.",
                "",
                "🔗 {$url}",
                "─────────────────────",
                "_RSUD H. Abdul Manap Kota Jambi_",
            ]),
            'pengaduan_selesai' => implode("\n", [
                "*HALO MANAP - Pengaduan Selesai*",
                "─────────────────────",
                "📋 *No:* {$nomor}",
                "✅ Pengaduan telah diselesaikan dan menunggu verifikasi admin.",
                "─────────────────────",
            ]),
            'pengaduan_ditutup' => implode("\n", [
                "*HALO MANAP - Pengaduan Ditutup*",
                "─────────────────────",
                "📋 *No:* {$nomor}",
                "🔒 Pengaduan telah diverifikasi dan ditutup.",
                "─────────────────────",
            ]),
            default => "*HALO MANAP*\nPengaduan {$nomor} mengalami perubahan status.",
        };
    }

    /**
     * Bangun pesan verifikasi untuk Admin Pengaduan.
     */
    private function buildAdminVerificationMessage($history, $ticket): string
    {
        $nomor     = $ticket->ticket_number ?? '-';
        $judul     = $ticket->title         ?? '-';
        $pelapor   = $ticket->is_anonymous ? 'Anonim' : ($ticket->reporter_name ?? '-');
        $unit      = $history->fromUnit?->nama ?? $history->toUnit?->nama ?? '-';
        $pj        = $history->fromUser?->nama ?? $history->toUser?->nama ?? '-';
        $url       = route('admin.tickets.show', $ticket->id);

        return implode("\n", [
            "*HALO MANAP - Verifikasi Diperlukan*",
            "─────────────────────",
            "📋 *No:* {$nomor}",
            "📝 *Judul:* {$judul}",
            "👤 *Pelapor:* {$pelapor}",
            "🏥 *Unit:* {$unit}",
            "✅ *Diselesaikan oleh:* {$pj}",
            "",
            "Pengaduan ini sudah selesai ditangani dan menunggu",
            "verifikasi Admin untuk ditutup.",
            "",
            "Silakan login untuk verifikasi:",
            "🔗 {$url}",
            "─────────────────────",
            "_RSUD H. Abdul Manap Kota Jambi_",
        ]);
    }

    /**
     * Kirim pesan WA dan simpan log.
     * TODO: Ganti implementasi ini dengan gateway WA yang sesuai (Fonnte, WA Cloud API, dll).
     */
    private function getInboxUrl(?User $user): string
    {
        if (!$user) return route('admin.tickets.index');

        $roleGroup = RoleMenuService::getRoleGroup($user);

        return match ($roleGroup) {
            'kepala_unit' => route('kepala-unit.dispositions.index'),
            'kasi'        => route('kasi.dispositions.index'),
            'kabid'       => route('kabid.dispositions.index'),
            'head_unit'   => route('head-unit.dispositions.index'),
            'direktur'    => route('direktur.dashboard'),
            default       => route('admin.tickets.index'),
        };
    }

    private function send(User $recipient, string $message, string $jenis, $history): void
    {
        try {
            $log = NotificationLog::create([
                'ticket_id'           => $history->ticket_id,
                'workflow_history_id' => $history->id,
                'recipient_user_id'   => $recipient->id,
                'nomor_wa'            => $recipient->phone_number,
                'jenis'               => $jenis,
                'isi_pesan'           => $message,
                'status'              => 'pending',
            ]);
        } catch (QueryException $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                Log::channel('daily')->warning('[WhatsApp] Duplicate skipped (atomic)', [
                    'workflow_history_id' => $history->id,
                    'recipient_user_id'   => $recipient->id,
                    'jenis'               => $jenis,
                ]);
                return;
            }
            throw $e;
        }

        $status = 'failed';
        $error  = null;

        $result = (new \App\Services\WhatsAppGatewayService())->sendText($recipient->phone_number, $message);

        if ($result['success']) {
            $status = 'sent';
        } else {
            $error = $result['error'] ?? 'unknown';
            Log::channel('daily')->warning('[WhatsApp API] Gagal', [
                'to'    => $recipient->phone_number,
                'error' => $error,
            ]);
        }

        $error = $error === null ? null : \Illuminate\Support\Str::limit($error, 250);

        $log->update([
            'status'        => $status,
            'error_message' => $error,
            'sent_at'       => $status === 'sent' ? now() : null,
        ]);
    }
}
