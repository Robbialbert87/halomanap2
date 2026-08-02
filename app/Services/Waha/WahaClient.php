<?php

declare(strict_types=1);

namespace App\Services\Waha;

use App\Models\Setting;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

final class WahaClient
{
    /** Retry transien: 3 percobaan (jarak 1s, 2s) untuk timeout/5xx/429. */
    private const RETRY_TIMES = 3;

    private const RETRY_SLEEP_MS = 1000;

    private function baseUrl(): string
    {
        return rtrim((string) Setting::getValue('waha_api_url', config('whatsapp.api_url')), '/');
    }

    private function apiKey(): string
    {
        $dbValue = Setting::getValue('waha_api_key');

        if (is_string($dbValue) && $dbValue !== '') {
            try {
                return Crypt::decryptString($dbValue);
            } catch (\Throwable) {
                return $dbValue;
            }
        }

        return (string) config('whatsapp.api_key', '');
    }

    /**
     * Kirim request ke API WAHA dengan autentikasi, retry, dan timeout.
     *
     * @param  array{json?: array, query?: array}  $options
     *
     * @throws WahaRequestException Saat semua percobaan gagal (jaringan / HTTP error).
     */
    public function request(string $method, string $path, array $options = []): Response
    {
        $client = Http::withHeaders([
            'X-Api-Key' => $this->apiKey(),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])
            ->baseUrl($this->baseUrl())
            ->connectTimeout(10)
            ->timeout(30)
            ->retry(self::RETRY_TIMES, self::RETRY_SLEEP_MS);

        try {
            $response = $client->send($method, $path, [
                'json' => $options['json'] ?? null,
                'query' => $options['query'] ?? [],
            ]);
        } catch (RequestException $e) {
            // 4xx/5xx setelah retry → konversi ke error terfokus (tanpa throw polos Laravel).
            $resp = $e->response;
            throw new WahaRequestException(
                sprintf('WAHA %s %s gagal (%d): %s', $method, $path, $resp->status(), $resp->body()),
                $resp->status(),
            );
        } catch (ConnectionException $e) {
            throw new WahaRequestException('WAHA API tidak dapat dijangkau: '.$e->getMessage(), 0, $e);
        }

        if ($response->successful()) {
            return $response;
        }

        // Jalur non-throw untuk status yang bersifat "terima tetapi bukan sukses".
        throw new WahaRequestException(
            sprintf('WAHA %s %s gagal (%d): %s', $method, $path, $response->status(), $response->body()),
            $response->status(),
        );
    }
}
