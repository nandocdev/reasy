<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Bookings\DTOs;

use Spatie\LaravelData\Data;

class ReserveSlotData extends Data
{
    public function __construct(
        public string $location_id,
        public string $service_id,
        public string $resource_id,
        public string $customer_id,
        public string $start_time,
        public string $end_time,
        public string $timezone,
        public ?string $customer_notes = null,
    ) {}
}
