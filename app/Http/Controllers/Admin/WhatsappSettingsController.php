<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendWhatsAppNotification;
use App\Models\NotificationLog;
use App\Models\Setting;
use App\Models\WhatsAppSession;
use App\Services\Waha\SessionService;
use App\Services\Waha\WahaRequestException;
use App\Services\Waha\WebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WhatsappSettingsController extends Controller
{
    public function __construct(
        private readonly SessionService $sessions,
        private readonly WebhookService $webhooks,
    ) {}

    private function apiUrl(): string
    {
        return rtrim($this->getConfig('api_url'), '/');
    }

    private function wahaHeaders(): array
    {
        return [
            'X-Api-Key' => $this->getConfig('api_key'),
            'Content-Type' => 'application/json',
        ];
    }

    private function getConfig(string $key): string
    {
        $map = [
            'api_url' => ['db' => 'waha_api_url', 'env' => 'whatsapp.api_url'],
            'api_key' => ['db' => 'waha_api_key', 'env' => 'whatsapp.api_key'],
            'session' => ['db' => 'waha_session', 'env' => 'whatsapp.session'],
        ];

        $cfg = $map[$key] ?? null;
        if (! $cfg) {
            return '';
        }

        $dbValue = Setting::getValue($cfg['db']);
        if ($dbValue && $key === 'api_key') {
            try {
                return Crypt::decryptString($dbValue);
            } catch (\Throwable) {
                return $dbValue;
            }
        }

        return $dbValue ?: config($cfg['env'], '');
    }

    public function index(): View
    {
        $wahaConfig = [
            'api_url' => Setting::getValue('waha_api_url', config('whatsapp.api_url')),
            'api_key' => Setting::getValue('waha_api_key', config('whatsapp.api_key')),
            'session' => Setting::getValue('waha_session', config('whatsapp.session')),
        ];

        $webhookConfig = [
            'url' => Setting::getValue('waha_webhook_url', route('waha.webhook')),
            'secret' => Setting::getValue('waha_webhook_secret', ''),
        ];

        $sessions = WhatsAppSession::with('user')->latest()->get();

        $failedLogs = NotificationLog::where('status', 'failed')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.whatsapp.index', [
            'wahaConfig' => $wahaConfig,
            'webhookConfig' => $webhookConfig,
            'sessions' => $sessions,
            'failedLogs' => $failedLogs,
        ]);
    }

    public function updateConfig(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'api_url' => 'required|url|max:255',
            'api_key' => 'required|string|max:255',
            'session' => 'required|string|max:100',
        ]);

        // Simpan config dasar
        Setting::setValue('waha_api_url', rtrim($validated['api_url'], '/'), 'WAHA API URL');
        Setting::setValue('waha_api_key', Crypt::encryptString($validated['api_key']), 'WAHA API Key (encrypted)');
        Setting::setValue('waha_session', $validated['session'], 'WAHA Session name');

        // Validasi session ada di WAHA (panggil API sessions/{name})
        try {
            $resp = Http::withHeaders([
                'X-Api-Key' => $validated['api_key'],
                'Content-Type' => 'application/json',
            ])->timeout(10)->get(rtrim($validated['api_url'], '/').'/api/sessions/'.$validated['session']);

            if ($resp->successful()) {
                $data = $resp->json();
                $status = $data['status'] ?? 'UNKNOWN';

                return redirect()->route('admin.whatsapp.index')
                    ->with('success', "Konfigurasi tersimpan. Status session: {$status}");
            }

            // Session tidak ditemukan di WAHA
            return redirect()->route('admin.whatsapp.index')
                ->with('error', "Session '{$validated['session']}' tidak ditemukan di WAHA. Pastikan session sudah dibuat dan aktif.");
        } catch (\Throwable $e) {
            return redirect()->route('admin.whatsapp.index')
                ->with('error', 'WAHA API tidak dapat dijangkau: '.$e->getMessage());
        }
    }

    public function sendTest(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        $phone = $validated['phone'];
        $message = $validated['message'];

        $chatId = Str::phone($phone);

        // Ambil session aktif dari service (bukan config default yang mungkin salah)
        $session = $this->sessions->getActiveSessionName();

        try {
            $resp = Http::withHeaders($this->wahaHeaders())
                ->timeout(15)
                ->post($this->apiUrl().'/api/sendText', [
                    'session' => $session,
                    'chatId' => $chatId,
                    'text' => $message,
                ]);

            if ($resp->successful() || $resp->status() === 201) {
                return redirect()->route('admin.whatsapp.index')
                    ->with('success', "Pesan berhasil dikirim ke {$phone}");
            }

            $body = $resp->json();
            $errorMsg = $body['error'] ?? $resp->body();

            return redirect()->route('admin.whatsapp.index')
                ->with('error', "Gagal: {$errorMsg}")
                ->withInput();
        } catch (\Throwable $e) {
            $errorMsg = $e->getMessage();

            if (str_contains($errorMsg, 'Connection refused') || str_contains($errorMsg, 'could not connect')) {
                $errorMsg = 'WAHA API tidak dapat dijangkau. Pastikan WAHA berjalan.';
            }

            return redirect()->route('admin.whatsapp.index')
                ->with('error', "Gagal: {$errorMsg}")
                ->withInput();
        }
    }

    public function checkStatus()
    {
        try {
            $session = $this->getConfig('session');
            $resp = Http::withHeaders($this->wahaHeaders())
                ->timeout(10)
                ->get($this->apiUrl().'/api/sessions/'.$session);

            if ($resp->successful()) {
                $data = $resp->json();
                $status = $data['status'] ?? 'STOPPED';
                $me = $data['me'] ?? null;

                return response()->json([
                    'success' => true,
                    'status' => $status,
                    'isAuthenticated' => $status === 'WORKING',
                    'me' => $me,
                    'session' => $session,
                ]);
            }

            if ($resp->status() === 404) {
                return response()->json([
                    'success' => false,
                    'status' => 'NOT_FOUND',
                    'isAuthenticated' => false,
                    'error' => "Session '{$session}' tidak ditemukan. Buat session di WAHA dashboard.",
                ]);
            }
        } catch (\Throwable $e) {
            //
        }

        return response()->json([
            'success' => false,
            'status' => 'OFFLINE',
            'isAuthenticated' => false,
            'error' => 'WAHA API tidak dapat dijangkau',
        ], 503);
    }

    public function storeSession(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'session_id' => 'required|string|max:255|unique:whatsapp_sessions',
            'phone_number' => 'required|string|max:20',
            'webhook_url' => 'nullable|url|max:255',
        ]);

        // Webhook otomatis dibuat saat session baru: URL default dari Setting DB
        // (fallback ke route receiver sendiri), secret HMAC dari Setting DB
        // (auto-generate bila belum ada), events status + message.
        $webhookUrl = $this->webhooks->resolveUrl($request->webhook_url);
        $webhookSecret = $this->webhooks->resolveSecret();
        $webhookEntry = $this->webhooks->buildEntry($webhookUrl, $webhookSecret);

        // Struktur WAHA: config.webhooks adalah ARRAY webhook entries.
        $sessionConfig = [
            'webhooks' => [$webhookEntry],
        ];

        try {
            $sessionStatus = $this->sessions->upsertAndStart($validated['session_id'], $sessionConfig);

            // Set session baru sebagai default kirim otomatis.
            Setting::setValue('waha_session', $validated['session_id'], 'WAHA Default Session');
        } catch (WahaRequestException $e) {
            return back()->withInput()->with('error', 'Gagal membuat session: '.$e->getMessage());
        }

        WhatsAppSession::create([
            'user_id' => auth()->id(),
            'session_id' => $validated['session_id'],
            'phone_number' => $validated['phone_number'],
            'status' => $sessionStatus->status,
            'webhook_config' => $webhookEntry,
        ]);

        return redirect()->route('admin.whatsapp.index')
            ->with('success', 'Session WhatsApp berhasil dibuat. Scan QR code untuk menghubungkan.');
    }

    public function updateWebhookConfig(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'webhook_url' => 'nullable|url|max:255',
            'webhook_secret' => 'nullable|string|max:255',
        ]);

        $webhookUrl = $validated['webhook_url'] ?? '';
        $webhookSecret = $validated['webhook_secret'] ?? '';

        if ($webhookUrl === '') {
            $webhookUrl = route('waha.webhook');
        }

        if ($webhookSecret === '') {
            $webhookSecret = Str::random(32);
        }

        Setting::setValue('waha_webhook_url', $webhookUrl, 'WAHA Webhook URL');
        Setting::setValue('waha_webhook_secret', $webhookSecret, 'WAHA Webhook HMAC Secret');

        return redirect()->route('admin.whatsapp.index')
            ->with('success', 'Konfigurasi webhook tersimpan. Berlaku untuk session baru.');
    }

    /**
     * Server-Sent Events: aliran status session via polling database ringan.
     * Dipakai EventSource di index (semua session) & show (?session=xxx, + QR).
     */
    public function streamStatus(Request $request): StreamedResponse
    {
        $requestedSession = (string) $request->query('session', '');
        $withQr = $requestedSession !== '';

        $snapshot = function () use ($requestedSession, $withQr): array {
            $query = WhatsAppSession::query()
                ->select(['session_id', 'phone_number', 'status', 'connected_at', 'disconnected_at', 'updated_at']);

            if ($withQr) {
                $query->addSelect('qr_code');
            }

            if ($withQr && $requestedSession !== '') {
                $query->where('session_id', $requestedSession);
            }

            return $query->get()->map(fn (WhatsAppSession $s): array => [
                'session_id' => $s->session_id,
                'phone_number' => $s->phone_number,
                'status' => $s->status,
                'qr_code' => $withQr ? $s->qr_code : null,
                'connected' => in_array($s->status, ['WORKING', 'CONNECTED'], true),
                'updated_at' => $s->updated_at?->timestamp,
            ])->values()->all();
        };

        return response()->stream(function () use ($snapshot): void {
            set_time_limit(0);

            $lastSignature = null;
            $first = true;

            while (true) {
                if (connection_aborted()) {
                    break;
                }

                $data = $snapshot();
                $signature = md5((string) json_encode($data));

                if ($first || $signature !== $lastSignature) {
                    echo "event: status\n";
                    echo 'data: '.json_encode($data, JSON_UNESCAPED_UNICODE)."\n\n";
                    $lastSignature = $signature;
                    $first = false;

                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();
                }

                echo ": ping\n\n";
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();

                sleep(1);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'X-Accel-Buffering' => 'no',
            'Connection' => 'keep-alive',
        ]);
    }

    public function showSession(string $sessionId): View
    {
        $session = WhatsAppSession::with('user')
            ->where('session_id', $sessionId)
            ->firstOrFail();

        $qr = null;
        $wahaInfo = [];

        try {
            $status = $this->sessions->get($sessionId);

            // Simpan state LIVES dari WAHA ke DB agar SSE & daftar ikut akurat.
            $session->update(array_filter([
                'status' => $status->status,
                'connected_at' => $status->isConnected() ? ($session->connected_at ?? now()) : null,
            ]));

            $wahaInfo = $status->raw;

            // QR HANYA valid/tersedia saat session benar2 dalam state SCAN_QR_CODE.
            if ($status->isScanning()) {
                $qr = $this->sessions->qr($sessionId)?->dataUri();
            }
        } catch (WahaRequestException) {
            // Session mungkin belum ada / WAHA tak terjangkau → tampilkan state DB.
        }

        $wahaConfig = [
            'api_url' => Setting::getValue('waha_api_url', config('whatsapp.api_url')),
            'api_key' => Setting::getValue('waha_api_key', config('whatsapp.api_key')),
            'session' => Setting::getValue('waha_session', config('whatsapp.session'))];

        return view('admin.whatsapp.show', [
            'session' => $session,
            'qr' => $qr,
            'wahaInfo' => $wahaInfo,
            'wahaConfig' => $wahaConfig,
        ]);
    }

    public function refreshQr(string $sessionId): JsonResponse
    {
        try {
            $status = $this->sessions->get($sessionId);

            if (! $status->isScanning()) {
                return response()->json(['error' => 'Session belum dalam state SCAN_QR_CODE ('.$status->status.')'], 409);
            }

            $qr = $this->sessions->qr($sessionId)?->dataUri();

            WhatsAppSession::where('session_id', $sessionId)->update([
                'qr_code' => $qr,
                'status' => 'scanning',
            ]);

            return response()->json(['qr' => $qr]);
        } catch (WahaRequestException $e) {
            return response()->json(['error' => $e->getMessage()], 502);
        }
    }

    public function sessionStatus(string $sessionId): JsonResponse
    {
        $session = WhatsAppSession::where('session_id', $sessionId)->firstOrFail();

        try {
            $status = $this->sessions->get($sessionId);
        } catch (WahaRequestException $e) {
            $session->update(['status' => 'NOT_CONNECTED']);

            return response()->json(['status' => 'NOT_CONNECTED', 'error' => $e->getMessage()]);
        }

        $session->update([
            'status' => $status->status,
            'connected_at' => $status->isConnected() ? now() : $session->connected_at,
        ]);

        return response()->json(['status' => $status->status, 'info' => $status->raw]);
    }

    public function syncSession(string $sessionId): JsonResponse
    {
        $session = WhatsAppSession::where('session_id', $sessionId)->firstOrFail();

        try {
            $status = $this->sessions->get($sessionId);
        } catch (WahaRequestException $e) {
            $session->update(['status' => 'NOT_CONNECTED']);

            return response()->json(['status' => 'NOT_CONNECTED', 'synced' => true, 'error' => $e->getMessage()]);
        }

        $session->update([
            'status' => $status->status,
            'connected_at' => $status->isConnected() ? now() : null,
        ]);

        return response()->json([
            'status' => $status->status,
            'synced' => true,
            'connected' => $status->isConnected(),
        ]);
    }

    public function syncAllSessions(Request $request): RedirectResponse
    {
        try {
            $resp = Http::withHeaders($this->wahaHeaders())
                ->timeout(30)
                ->get($this->apiUrl().'/api/sessions');

            throw_if($resp->failed(), new \RuntimeException('WAHA unreachable'));

            $remoteSessions = $resp->json();
            $synced = 0;

            foreach ($remoteSessions as $remote) {
                $sid = $remote['name'] ?? $remote['id'] ?? null;
                if (! $sid) {
                    continue;
                }

                $status = $remote['status'] ?? 'UNKNOWN';
                $me = $remote['me'] ?? [];
                $phone = is_string($me) ? $me : ($me['user'] ?? $me['id'] ?? '');

                WhatsAppSession::updateOrCreate(
                    ['session_id' => $sid],
                    [
                        'user_id' => auth()->id(),
                        'phone_number' => $phone ?: '',
                        'status' => $status ?: 'UNKNOWN',
                        'connected_at' => $status === 'CONNECTED' ? now() : null,
                    ]
                );
                $synced++;
            }

            return redirect()->route('admin.whatsapp.index')
                ->with('success', "Sinkronisasi selesai: {$synced} session dari server.");
        } catch (\Throwable $e) {
            return redirect()->route('admin.whatsapp.index')
                ->with('error', 'Gagal sinkronisasi: '.$e->getMessage());
        }
    }

    public function disconnectSession(string $sessionId): RedirectResponse
    {
        try {
            $this->sessions->logout($sessionId);
        } catch (WahaRequestException $e) {
            return back()->with('error', 'Gagal disconnect: '.$e->getMessage());
        }

        WhatsAppSession::where('session_id', $sessionId)->update([
            'status' => 'disconnected',
            'disconnected_at' => now(),
        ]);

        return redirect()->route('admin.whatsapp.index')
            ->with('success', 'Session WhatsApp berhasil diputuskan.');
    }

    public function deleteSession(string $sessionId): RedirectResponse
    {
        try {
            $this->sessions->delete($sessionId);
        } catch (WahaRequestException $e) {
            return back()->with('error', 'Gagal menghapus session: '.$e->getMessage());
        }

        WhatsAppSession::where('session_id', $sessionId)->delete();

        return redirect()->route('admin.whatsapp.index')
            ->with('success', 'Session WhatsApp berhasil dihapus.');
    }

    public function startSession(string $sessionId): RedirectResponse
    {
        try {
            $status = $this->sessions->start($sessionId);
        } catch (WahaRequestException $e) {
            return back()->with('error', 'Gagal menjalankan session: '.$e->getMessage());
        }

        WhatsAppSession::where('session_id', $sessionId)->update([
            'status' => $status->status,
            'disconnected_at' => null,
        ]);

        return redirect()->route('admin.whatsapp.sessions.show', $sessionId)
            ->with('success', 'Session sedang dijalankan. Scan QR untuk menghubungkan.');
    }

    public function restartSession(string $sessionId): RedirectResponse
    {
        try {
            $status = $this->sessions->restart($sessionId);
        } catch (WahaRequestException $e) {
            return back()->with('error', 'Gagal me-restart session: '.$e->getMessage());
        }

        WhatsAppSession::where('session_id', $sessionId)->update([
            'status' => $status->status,
            'disconnected_at' => null,
        ]);

        return redirect()->route('admin.whatsapp.sessions.show', $sessionId)
            ->with('success', 'Session di-restart. Scan QR untuk menghubungkan.');
    }

    public function showFailed(Request $request): View|string
    {
        $failedLogs = NotificationLog::where('status', 'failed')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = trim($request->search);
                $q->where(function ($sub) use ($search) {
                    $sub->where('nomor_wa', 'like', "%{$search}%")
                        ->orWhere('isi_pesan', 'like', "%{$search}%")
                        ->orWhere('error_message', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('jenis'), function ($q) use ($request) {
                $q->where('jenis', $request->jenis);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $jenisList = NotificationLog::where('status', 'failed')
            ->select('jenis')
            ->distinct()
            ->orderBy('jenis')
            ->pluck('jenis');

        // Live filter: balas fragment (tabel) untuk fetch tanpa reload
        return view('admin.whatsapp.resend', ['failedLogs' => $failedLogs, 'jenisList' => $jenisList])
            ->fragmentIf($request->header('X-Live-Filter') === '1', 'results');
    }

    public function resendSubmit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:notification_logs,id',
        ]);

        $resent = 0;
        $skipped = 0;
        $logs = NotificationLog::whereIn('id', $validated['ids'])->get();

        foreach ($logs as $log) {
            // Atomic claim: hanya log berstatus failed yang dikirim ulang.
            // Update ke 'pending' sebagai in-flight → cegah double-submit / resend ganda.
            $claimed = NotificationLog::where('id', $log->id)
                ->where('status', 'failed')
                ->update(['status' => 'pending', 'error_message' => null, 'sent_at' => null]);

            if ($claimed === 0) {
                $skipped++;

                continue;
            }

            SendWhatsAppNotification::dispatch($log->nomor_wa, $log->isi_pesan, $log->id);
            $resent++;
        }

        $message = "{$resent} notifikasi sedang dikirim ulang.";
        if ($skipped > 0) {
            $message .= " {$skipped} dilewati (sudah pernah dikirim/diproses).";
        }

        return redirect()->route('admin.whatsapp.resend')
            ->with('success', $message);
    }

    public function saveBarcodeUrl(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'barcode_url' => 'required|url|max:255',
        ]);

        Setting::setValue('barcode_url', $validated['barcode_url'], 'URL untuk QR Code barcode pengaduan');

        return redirect()->route('admin.whatsapp.index')
            ->with('success', 'URL barcode berhasil disimpan.');
    }

    public function setDefaultSession(string $sessionId): RedirectResponse
    {
        $session = WhatsAppSession::where('session_id', $sessionId)->first();

        if (! $session) {
            return redirect()->route('admin.whatsapp.index')
                ->with('error', 'Session tidak ditemukan.');
        }

        Setting::setValue('waha_session', $sessionId, 'WAHA Default Session');

        return redirect()->route('admin.whatsapp.index')
            ->with('success', "Session {$sessionId} sekarang menjadi default untuk kirim pesan.");
    }
}
