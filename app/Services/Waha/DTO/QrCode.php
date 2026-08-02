<?php

declare(strict_types=1);

namespace App\Services\Waha\DTO;

/**
 * Query state (payload) dari pertanyaan QR base64.
 */
final readonly class QrCode
{
    public function __construct(
        public ?string $base64 = null,
        public ?string $raw = null,
        public string $mimetype = 'image/png',
    ) {}

    public function dataUri(): ?string
    {
        if ($this->base64 !== null && $this->base64 !== '') {
            return 'data:'.$this->mimetype.';base64,'.$this->base64;
        }

        return $this->raw;
    }
}
