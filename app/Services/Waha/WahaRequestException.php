<?php

declare(strict_types=1);

namespace App\Services\Waha;

use RuntimeException;

/**
 * Error terfokus satu dari semua panggilan HTTP ke WAHA.
 */
final class WahaRequestException extends RuntimeException
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
