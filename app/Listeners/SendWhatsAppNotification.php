<?php

namespace App\Listeners;

use App\Events\WorkflowChanged;
use App\Models\NotificationLog;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Nama queue yang akan digunakan.
     */
    public string $queue = 'notifications';

    public function handle(WorkflowChanged $event): void
    {
        $history = $event->history;
        $jenis   = $event->jenis;
        $ticket  = $history->ticket;

        // ─── 1. Kirim ke User Penerima (To User) ────────────────────────────
        if ($history->toUser && $history->toUser->phone_number) {
            $message = $this->buildMessage($jenis, $history, $ticket);
            $this->send($history->toUser, $message, $jenis, $history);
        }

        // ─── 2. Kirim Monitoring ke Direktur ────────────────────────────────
        // Direktur adalah observer: menerima WA untuk setiap perubahan workflow penting
        if (in_array($jenis, ['disposisi_baru', 'eskalasi', 'pengaduan_selesai', 'pengaduan_ditutup'])) {
            $direkturs = $this->getDirekturs($history->to_unit_id);
            foreach ($direkturs as $direktur) {
                if ($direktur->phone_number) {
                    $dirMessage = $this->buildMonitoringMessage($history, $ticket);
                    $this->send($direktur, $dirMessage, 'monitoring_direktur', $history);
                }
            }
        }

        // ─── 3. Notifikasi Admin Pengaduan saat pengaduan menunggu verifikasi ─
        // Ketika Kepala Unit/Kasi/Kabid klik Selesai, admin perlu diingatkan
        // untuk memverifikasi dan menutup pengaduan.
        if ($jenis === 'pengaduan_selesai') {
            $admins = User::role('Admin Pengaduan')->whereNotNull('phone_number')->get();
            $adminMessage = $this->buildAdminVerificationMessage($history, $ticket);
            foreach ($admins as $admin) {
                $this->send($admin, $adminMessage, 'admin_verifikasi', $history);
            }
        }
    }

    private function getDirekturs(?int $unitId): \Illuminate\Support\Collection
    {
        $jabatanDirektur = Jabatan::where('kategori_jabatan', 'Direktur')
            ->where('status', 'active')
            ->pluck('id');

        return User::whereIn('jabatan_id', $jabatanDirektur)
            ->where('status', 'active')
            ->get();
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
        $url       = config('app.url') . '/admin/tickets';

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
     * Bangun pesan khusus monitoring untuk Direktur.
     */
    private function buildMonitoringMessage($history, $ticket): string
    {
        $toJabatan = $history->toJabatan?->nama ?? '-';
        $toUnit    = $history->toUnit?->nama    ?? '-';
        $toUser    = $history->toUser?->nama    ?? 'Belum ditugaskan';
        $nomor     = $ticket->ticket_number     ?? '-';
        $judul     = $ticket->title             ?? '-';
        $status    = $history->status;
        $url       = config('app.url') . '/admin/monitoring';

        return implode("\n", [
            "*MONITORING HALO MANAP*",
            "─────────────────────",
            "📋 *No Pengaduan:* {$nomor}",
            "📝 *Judul:* {$judul}",
            "🏥 *Unit:* {$toUnit}",
            "📊 *Status:* " . strtoupper(str_replace('_', ' ', $status)),
            "👤 *Penanggung Jawab:* {$toUser}",
            "🏷️ *Jabatan:* {$toJabatan}",
            "",
            "Silakan buka Dashboard Monitoring untuk detail.",
            "🔗 {$url}",
            "─────────────────────",
            "_RSUD H. Abdul Manap Kota Jambi_",
        ]);
    }

    /**
     * Bangun pesan verifikasi untuk Admin Pengaduan.
     */
    private function buildAdminVerificationMessage($history, $ticket): string
    {
        $nomor     = $ticket->ticket_number ?? '-';
        $judul     = $ticket->title         ?? '-';
        $pelapor   = $ticket->is_anonymous ? 'Anonim' : ($ticket->reporter_name ?? '-');
        $unit      = $history->toUnit?->nama ?? '-';
        $pj        = $history->toUser?->nama ?? '-';
        $url       = config('app.url') . '/admin/tickets/' . $ticket->id;

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
    private function send(User $recipient, string $message, string $jenis, $history): void
    {
        $status = 'failed';
        $error  = null;

        try {
            $response = Http::timeout(15)->post('http://localhost:3000/send', [
                'number'  => $recipient->phone_number,
                'message' => $message,
            ]);

            if ($response->successful()) {
                $status = 'sent';
            } else {
                $error = $response->body();
                Log::channel('daily')->warning('[WhatsApp API] Gagal', [
                    'to'    => $recipient->phone_number,
                    'error' => $error,
                ]);
            }

        } catch (\Throwable $e) {
            $error = $e->getMessage();
            Log::channel('daily')->error('[WhatsApp API] ' . $e->getMessage(), [
                'to' => $recipient->phone_number,
            ]);
        }

        NotificationLog::create([
            'ticket_id'           => $history->ticket_id,
            'workflow_history_id' => $history->id,
            'recipient_user_id'   => $recipient->id,
            'nomor_wa'            => $recipient->phone_number,
            'jenis'               => $jenis,
            'isi_pesan'           => $message,
            'status'              => $status,
            'error_message'       => $error,
            'sent_at'             => $status === 'sent' ? now() : null,
        ]);
    }
}
