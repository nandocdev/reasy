<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Bookings\DTOs;

use Spatie\LaravelData\Data;

class CompleteBookingData extends Data
{
    public function __construct(
        public string $booking_id,
        public ?string $notes = null,
    ) {}
}
