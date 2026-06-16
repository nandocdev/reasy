<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Bookings\DTOs;

use Spatie\LaravelData\Data;

class CancelBookingData extends Data
{
    public function __construct(
        public string $booking_id,
        public string $reason,
        public bool $adminOverride = false,
    ) {}
}
