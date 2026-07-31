<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendWhatsAppNotification;
use App\Models\NotificationLog;
use App\Models\Setting;
use App\Models\WhatsAppSession;
use App\Services\WahaApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WhatsappSettingsController extends Controller
{
    public function __construct(
        private readonly WahaApiService $waha,
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

        $sessions = WhatsAppSession::with('user')->latest()->get();

        $failedLogs = NotificationLog::where('status', 'failed')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.whatsapp.index', ['wahaConfig' => $wahaConfig, 'sessions' => $sessions, 'failedLogs' => $failedLogs]);
    }

    public function updateConfig(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'api_url' => 'required|url|max:255',
            'api_key' => 'required|string|max:255',
            'session' => 'required|string|max:100',
        ]);

        Setting::setValue('waha_api_url', rtrim($validated['api_url'], '/'), 'WAHA API URL');
        Setting::setValue('waha_api_key', Crypt::encryptString($validated['api_key']), 'WAHA API Key (encrypted)');
        Setting::setValue('waha_session', $validated['session'], 'WAHA Session name');

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

            return redirect()->route('admin.whatsapp.index')
                ->with('success', 'Konfigurasi tersimpan, tetapi session tidak ditemukan. Buat session di WAHA dashboard.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.whatsapp.index')
                ->with('success', 'Konfigurasi tersimpan. WAHA API tidak dapat dijangkau, periksa URL dan koneksi.');
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

        try {
            $resp = Http::withHeaders($this->wahaHeaders())
                ->timeout(15)
                ->post($this->apiUrl().'/api/sendText', [
                    'session' => $this->getConfig('session'),
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

        $sessionConfig = [
            'webhook' => $request->filled('webhook_url') ? [
                'url' => $request->webhook_url,
                'events' => ['message'],
            ] : null,
        ];

        try {
            $this->waha->upsertAndStartSession($validated['session_id'], $sessionConfig);
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', 'Gagal membuat session: '.$e->getMessage());
        }

        WhatsAppSession::create([
            'user_id' => auth()->id(),
            'session_id' => $validated['session_id'],
            'phone_number' => $validated['phone_number'],
            'status' => 'scanning',
            'webhook_config' => $sessionConfig['webhook'],
        ]);

        return redirect()->route('admin.whatsapp.index')
            ->with('success', 'Session WhatsApp berhasil dibuat. Scan QR code untuk menghubungkan.');
    }

    public function showSession(string $sessionId): View
    {
        $session = WhatsAppSession::with('user')
            ->where('session_id', $sessionId)
            ->firstOrFail();

        $qr = null;
        $wahaInfo = [];

        try {
            $wahaInfo = $this->waha->getSession($sessionId);
        } catch (\RuntimeException) {
            //
        }

        try {
            $qr = $this->waha->getQr($sessionId);
        } catch (\RuntimeException) {
            //
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
            $qr = $this->waha->getQr($sessionId);

            WhatsAppSession::where('session_id', $sessionId)->update([
                'qr_code' => $qr,
                'status' => 'scanning',
            ]);

            return response()->json(['qr' => $qr]);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 502);
        }
    }

    public function sessionStatus(string $sessionId): JsonResponse
    {
        $session = WhatsAppSession::where('session_id', $sessionId)->firstOrFail();

        try {
            $info = $this->waha->getSession($sessionId);
            $status = $info['status'] ?? 'unknown';
        } catch (\RuntimeException $e) {
            $session->update(['status' => 'NOT_CONNECTED']);

            return response()->json(['status' => 'NOT_CONNECTED', 'error' => $e->getMessage()]);
        }

        $session->update([
            'status' => $status,
            'connected_at' => $status === 'CONNECTED' ? now() : $session->connected_at,
        ]);

        return response()->json(['status' => $status, 'info' => $info]);
    }

    public function syncSession(string $sessionId): JsonResponse
    {
        $session = WhatsAppSession::where('session_id', $sessionId)->firstOrFail();

        try {
            $info = $this->waha->getSession($sessionId);
            $status = $info['status'] ?? 'NOT_CONNECTED';
            $me = $status === 'CONNECTED' ? $this->waha->getSession($sessionId) : [];
        } catch (\RuntimeException $e) {
            $session->update(['status' => 'NOT_CONNECTED']);

            return response()->json(['status' => 'NOT_CONNECTED', 'synced' => true]);
        }

        $session->update([
            'status' => $status,
            'connected_at' => $status === 'CONNECTED' ? now() : null,
        ]);

        return response()->json([
            'status' => $status,
            'synced' => true,
            'connected' => $status === 'CONNECTED',
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
            $this->waha->logout($sessionId);
        } catch (\RuntimeException $e) {
            return back()->with('error', 'Gagal disconnect: '.$e->getMessage());
        }

        WhatsAppSession::where('session_id', $sessionId)->update([
            'status' => 'disconnected',
        ]);

        return redirect()->route('admin.whatsapp.index')
            ->with('success', 'Session WhatsApp berhasil diputuskan.');
    }

    public function deleteSession(string $sessionId): RedirectResponse
    {
        try {
            $this->waha->deleteSession($sessionId);
        } catch (\RuntimeException $e) {
            return back()->with('error', 'Gagal menghapus session: '.$e->getMessage());
        }

        WhatsAppSession::where('session_id', $sessionId)->delete();

        return redirect()->route('admin.whatsapp.index')
            ->with('success', 'Session WhatsApp berhasil dihapus.');
    }

    public function showFailed(Request $request): View
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
        if ($request->header('X-Live-Filter') === '1') {
            return view('admin.whatsapp._resend_table', ['failedLogs' => $failedLogs]);
        }

        return view('admin.whatsapp.resend', ['failedLogs' => $failedLogs, 'jenisList' => $jenisList]);
    }

    public function resendSubmit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:notification_logs,id',
        ]);

        $resent = 0;
        $logs = NotificationLog::whereIn('id', $validated['ids'])->get();

        foreach ($logs as $log) {
            SendWhatsAppNotification::dispatch($log->nomor_wa, $log->isi_pesan);
            $resent++;
        }

        return redirect()->route('admin.whatsapp.resend')
            ->with('success', "{$resent} notifikasi sedang dikirim ulang.");
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
