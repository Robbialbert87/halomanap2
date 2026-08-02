<?php

declare(strict_types=1);

namespace App\Services\Waha;

use App\Models\Setting;
use Illuminate\Support\Str;

/**
 * Membangun & menerapkan konfigurasi webhook WAHA (session.status, message)
 * ke satu session di server WAHA.
 */
final class WebhookService
{
    public function __construct(
        private readonly WahaClient $client,
        private readonly SessionService $sessions,
    ) {}

    /**
     * Resolusi URL webhook: eksplisit > Setting DB > route default.
     */
    public function resolveUrl(?string $explicit = null): string
    {
        if (is_string($explicit) && $explicit !== '') {
            return $explicit;
        }

        $dbUrl = Setting::getValue('waha_webhook_url', '');

        if (is_string($dbUrl) && $dbUrl !== '') {
            return $dbUrl;
        }

        return (string) route('waha.webhook');
    }

    /**
     * Secret HMAC: dari Setting DB, auto-generate bila belum ada.
     */
    public function resolveSecret(?string $explicit = null): string
    {
        if (is_string($explicit) && $explicit !== '') {
            return $explicit;
        }

        $secret = Setting::getValue('waha_webhook_secret');

        if (is_string($secret) && $secret !== '') {
            return $secret;
        }

        $secret = Str::random(32);
        Setting::setValue('waha_webhook_secret', $secret, 'WAHA Webhook HMAC Secret');

        return $secret;
    }

    /**
     * Struktur entri webhook yang diterima WAHA.
     *
     * @return array{url: string, events: string[], hmac: array{key: string}}
     */
    public function buildEntry(?string $url = null, ?string $secret = null): array
    {
        return [
            'url' => $this->resolveUrl($url),
            'events' => ['session.status', 'message'],
            'hmac' => ['key' => $this->resolveSecret($secret)],
        ];
    }

    /**
     * Pasang webhook ke session (existing). return payload response WAHA.
     */
    public function applyToSession(string $sessionId, string $webhookUrl, string $secret): array
    {
        return $this->sessions->updateConfig($sessionId, [
            'webhooks' => [
                [
                    'url' => $webhookUrl,
                    'events' => ['session.status', 'message'],
                    'hmac' => ['key' => $secret],
                ],
            ],
        ]);
    }
}
