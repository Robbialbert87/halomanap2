<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\WhatsAppSession;
use App\Services\WahaApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class WahaWebhookController extends Controller
{
    /** Status yang berarti session terputus/koneksi mati. */
    private const DISCONNECTED_STATUSES = ['DISCONNECTED', 'TIMEOUT', 'FAILED', 'STOPPED', 'DESTROYED'];

    public function __construct(
        private readonly WahaApiService $waha,
    ) {}

    /**
     * Terima event webhook dari WAHA (session.status, message, dsb.).
     *
     * Validasi HMAC memakai secret per-session (webhook_config.hmac.key),
     * dengan fallback ke Setting DB `waha_webhook_secret`. Jika tidak ada
     * secret sama sekali (session lama), request diterima tanpa validasi.
     */
    public function handle(Request $request): JsonResponse
    {
        $rawBody = $request->getContent();
        $event = (string) $request->input('event', '');
        $sessionName = (string) $request->input('session', '');
        $status = (string) ($request->input('payload.status') ?? '');

        $session = WhatsAppSession::where('session_id', $sessionName)->first();

        if (! $this->signatureIsValid($request, $rawBody, $session)) {
            return response()->json(['error' => 'Invalid HMAC signature'], 401);
        }

        if ($event === 'session.status' && $session) {
            $this->applySessionStatus($session, $status);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Validasi tanda tangan HMAC yang dikirim WAHA via header
     * `X-Webhook-Hmac` (hex, algoritma dari `X-Webhook-Hmac-Algorithm`).
     *
     * - WAHA tidak mengirim signature → webhook lama/tanpa HMAC → terima.
     * - WAHA mengirim signature → wajib cocok dengan secret per-session
     *   (webhook_config.hmac.key) atau fallback Setting DB `waha_webhook_secret`.
     */
    private function signatureIsValid(Request $request, string $rawBody, ?WhatsAppSession $session): bool
    {
        $signature = (string) $request->header('X-Webhook-Hmac', '');

        if ($signature === '') {
            return true;
        }

        $secret = $session?->webhook_config['hmac']['key'] ?? null;
        $secret = is_string($secret) && $secret !== '' ? $secret : Setting::getValue('waha_webhook_secret');

        if (! is_string($secret) || $secret === '') {
            Log::warning('WAHA webhook mengirim signature tetapi tidak ada secret terkonfigurasi', [
                'session' => $request->input('session'),
            ]);

            return false;
        }

        $algorithm = (string) $request->header('X-Webhook-Hmac-Algorithm', 'sha512');
        $expected = hash_hmac($algorithm, $rawBody, $secret);

        return hash_equals($expected, $signature);
    }

    /**
     * Terapkan status session dari event `session.status` ke database.
     * Saat QR berubah (SCAN_QR_CODE) QR baru di-fetch dari WAHA.
     */
    private function applySessionStatus(WhatsAppSession $session, string $status): void
    {
        if ($status === '') {
            return;
        }

        $data = ['status' => $status];

        if ($status === 'SCAN_QR_CODE') {
            try {
                $qr = $this->waha->getQr($session->session_id);

                if (is_string($qr) && $qr !== '') {
                    $data['qr_code'] = $qr;
                }
            } catch (\Throwable $e) {
                Log::warning('Gagal fetch QR dari webhook SCAN_QR_CODE', [
                    'session' => $session->session_id,
                    'error' => $e->getMessage(),
                ]);
            }
        } elseif ($status === 'WORKING') {
            $data['connected_at'] = now();
            $data['disconnected_at'] = null;
        } elseif (in_array($status, self::DISCONNECTED_STATUSES, true)) {
            $data['disconnected_at'] = now();
        }

        $session->update($data);
    }
}
