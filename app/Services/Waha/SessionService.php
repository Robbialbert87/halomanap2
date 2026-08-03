<?php

declare(strict_types=1);

namespace App\Services\Waha;

use App\Models\Setting;
use App\Services\Waha\DTO\QrCode;
use App\Services\Waha\DTO\SessionStatus;

final class SessionService
{
    public function __construct(
        private readonly WahaClient $client,
    ) {}

    /**
     * Buat (upsert) & jalankan session dengan konfigurasi.
     */
    public function upsertAndStart(string $name, array $sessionConfig = []): SessionStatus
    {
        $response = $this->client->request('POST', '/api/sessions/start', [
            'json' => ['name' => $name, 'config' => $sessionConfig],
        ]);

        return $this->toStatus($response->json() ?? []);
    }

    public function start(string $sessionId): SessionStatus
    {
        return $this->toStatus($this->json('POST', "/api/sessions/{$sessionId}/start"));
    }

    public function restart(string $sessionId): SessionStatus
    {
        return $this->toStatus($this->json('POST', "/api/sessions/{$sessionId}/restart"));
    }

    public function logout(string $sessionId): void
    {
        $this->client->request('POST', "/api/sessions/{$sessionId}/logout");
    }

    public function delete(string $sessionId): void
    {
        $this->client->request('DELETE', "/api/sessions/{$sessionId}");
    }

    public function get(string $sessionId): SessionStatus
    {
        return $this->toStatus($this->json('GET', "/api/sessions/{$sessionId}"));
    }

    /**
     * Cek hidup/tidaknya session. return null bila session tidak ditemukan.
     */
    public function getOptional(string $sessionId): ?SessionStatus
    {
        $response = $this->client->request('GET', "/api/sessions/{$sessionId}");

        return $this->toStatus($response->json() ?? []);
    }

    public function updateConfig(string $sessionId, array $config): array
    {
        $response = $this->client->request('PUT', "/api/sessions/{$sessionId}", [
            'json' => ['name' => $sessionId, 'config' => $config],
        ]);

        return $response->json() ?? [];
    }

    /**
     * Ambil QR base64. Return null bila session belum dalam state SCAN_QR_CODE.
     */
    public function qr(string $sessionId): ?QrCode
    {
        $response = $this->client->request('GET', "/api/{$sessionId}/auth/qr");

        $json = $response->json() ?? [];

        return new QrCode(
            base64: is_string($json['data'] ?? null)
                ? $json['data']
                : (is_string($json['base64'] ?? null) ? $json['base64'] : null),
            mimetype: is_string($json['mimetype'] ?? null) ? $json['mimetype'] : 'image/png',
        );
    }

    /**
     * @return array{0: string, 1: int|null, 2: string}
     */
    private function json(string $method, string $path): array
    {
        return $this->client->request($method, $path)->json() ?? [];
    }

    private function toStatus(array $data): SessionStatus
    {
        return new SessionStatus(
            name: (string) ($data['name'] ?? ''),
            status: strtoupper((string) ($data['status'] ?? 'UNKNOWN')),
            raw: $data,
        );
    }

    /**
     * Ambil nama session yang statusnya WORKING atau SCAN_QR_CODE.
     * Jika tidak ada, fallback ke setting waha_session atau env WAHA_SESSION atau 'def'.
     */
    public function getActiveSessionName(): string
    {
        try {
            $response = $this->client->request('GET', '/api/sessions');
            $sessions = $response->json() ?? [];

            foreach ($sessions as $s) {
                $status = $s['status'] ?? '';
                if (in_array($status, ['WORKING', 'SCAN_QR_CODE'], true)) {
                    return $s['name'] ?? $s['session'] ?? $s['session_id'] ?? 'def';
                }
            }
        } catch (\Throwable $e) {
            // log error jika perlu
        }

        return Setting::getValue('waha_session') ?? env('WAHA_SESSION', 'def');
    }
}
