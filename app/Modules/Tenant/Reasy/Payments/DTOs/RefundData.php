<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Payments\DTOs;

use Spatie\LaravelData\Data;

final class RefundData extends Data
{
    public function __construct(
        public readonly string $booking_id,
        public readonly float $amount,
        public readonly string $reason,
    ) {}
}
