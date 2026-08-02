<?php

declare(strict_types=1);

namespace App\Services\Waha\DTO;

/**
 * Info satu session WAHA dari endpoint /api/sessions/{name}.
 */
final readonly class SessionStatus
{
    /**
     * @param  string  $name  Nama session
     * @param  string  $status  WORKING / SCAN_QR_CODE / STARTING / FAILED / ...
     * @param  array<string, mixed>  $raw  Payload mentah dari WAHA
     */
    public function __construct(
        public string $name,
        public string $status,
        public array $raw,
    ) {}

    public function isConnected(): bool
    {
        return in_array($this->status, ['WORKING', 'CONNECTED'], true);
    }

    public function isScanning(): bool
    {
        return in_array($this->status, ['SCAN_QR_CODE', 'SCANNING', 'STARTING', 'creating'], true);
    }
}
