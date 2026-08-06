<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppGatewayService
{
    private string $baseUrl;
    private string $apiKey;
    private string $session;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.waha.base_url'), '/');
        $this->apiKey  = (string) config('services.waha.api_key');
        $this->session = (string) config('services.waha.session', 'default');
    }

    public function session(): string
    {
        return $this->session;
    }

    /**
     * Normalisasi nomor HP menjadi format internasional (62...).
     */
    public function normalizeNumber(string $number): string
    {
        $number = preg_replace('/\D/', '', $number);

        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        } elseif (str_starts_with($number, '8')) {
            $number = '62' . $number;
        }

        return $number;
    }

    /**
     * Kirim pesan teks WhatsApp via WAHA.
     */
    public function sendText(string $number, string $text): array
    {
        $chatId = $this->normalizeNumber($number) . '@c.us';

        try {
            $response = $this->client()->post($this->url('/api/sendText'), [
                'session' => $this->session,
                'chatId'  => $chatId,
                'text'    => $text,
            ]);

            if ($response->successful()) {
                return ['success' => true, 'response' => $response->json()];
            }

            Log::channel('daily')->warning('[WAHA sendText] Gagal', [
                'chatId' => $chatId,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return ['success' => false, 'error' => $response->body()];
        } catch (\Throwable $e) {
            Log::channel('daily')->error('[WAHA sendText] ' . $e->getMessage(), ['chatId' => $chatId]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Ambil status session dari WAHA.
     * State: STOPPED, STARTING, SCAN_QR_CODE, WORKING, FAILED.
     * Return null jika server WAHA tidak bisa dihubungi.
     */
    public function state(): ?string
    {
        try {
            $response = $this->client()->get($this->url('/api/sessions/' . $this->session));
            if ($response->successful()) {
                $data = $response->json();
                return (string) ($data['status'] ?? 'STOPPED');
            }
        } catch (\Throwable $e) {
            Log::channel('daily')->error('[WAHA state] ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Ambil QR code dalam bentuk data URL.
     */
    public function qr(): ?string
    {
        try {
            $response = $this->client()
                ->withHeaders(['Accept' => 'application/json'])
                ->get($this->url('/api/' . $this->session . '/auth/qr'));

            if ($response->successful()) {
                $data = $response->json();
                $mime = $data['mimetype'] ?? 'image/png';
                $base = $data['data'] ?? null;
                if ($base) {
                    return 'data:' . $mime . ';base64,' . $base;
                }
            }
        } catch (\Throwable $e) {
            Log::channel('daily')->error('[WAHA qr] ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Logout session WAHA (putuskan tautan nomor).
     */
    public function logout(): bool
    {
        try {
            $response = $this->client()->post($this->url('/api/sessions/' . $this->session . '/logout'));
            return $response->successful();
        } catch (\Throwable $e) {
            Log::channel('daily')->error('[WAHA logout] ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Start session WAHA.
     */
    public function start(): bool
    {
        try {
            $response = $this->client()->post($this->url('/api/sessions/' . $this->session . '/start'));
            return $response->successful();
        } catch (\Throwable $e) {
            Log::channel('daily')->error('[WAHA start] ' . $e->getMessage());
            return false;
        }
    }

    private function client()
    {
        return Http::timeout(15)->withHeaders(['X-Api-Key' => $this->apiKey]);
    }

    private function url(string $path): string
    {
        return $this->baseUrl . $path;
    }
}
