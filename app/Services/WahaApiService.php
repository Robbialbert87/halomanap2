<?php

declare(strict_types=1);

namespace App\Services;

use CCK\LaravelWahaSaloonSdk\Facades\Waha;

final class WahaApiService
{
    public function upsertAndStartSession(string $name, array $sessionConfig = []): array
    {
        $response = Waha::start()->upsertAndStartSession($name, $sessionConfig);

        if ($response->failed()) {
            throw new \RuntimeException('WAHA session creation failed: '.$response->body());
        }

        return $response->json();
    }

    public function startSession(string $sessionId): array
    {
        $response = Waha::start()->startTheSession($sessionId);

        if ($response->failed()) {
            throw new \RuntimeException('WAHA session start failed: '.$response->body());
        }

        return $response->json();
    }

    public function getQr(string $sessionId): ?string
    {
        $response = Waha::qr()->getQrCodeBase64($sessionId);

        if ($response->failed()) {
            throw new \RuntimeException('WAHA QR fetch failed: '.$response->body());
        }

        $json = $response->json();
        $base64 = $json['base64'] ?? $json['image'] ?? $json['data'] ?? null;

        if ($base64) {
            return 'data:image/png;base64,'.$base64;
        }

        return $response->body();
    }

    public function getSession(string $sessionId): array
    {
        $response = Waha::sessions()->getSessionInformation($sessionId);

        if ($response->failed()) {
            throw new \RuntimeException('WAHA session fetch failed: '.$response->body());
        }

        return $response->json();
    }

    public function deleteSession(string $sessionId): void
    {
        $response = Waha::logout()->logoutAndDeleteSession($sessionId);

        if ($response->failed()) {
            throw new \RuntimeException('WAHA session deletion failed: '.$response->body());
        }
    }

    public function logout(string $sessionId): void
    {
        $response = Waha::logout()->logoutFromTheSession($sessionId);

        if ($response->failed()) {
            throw new \RuntimeException('WAHA session logout failed: '.$response->body());
        }
    }
}
