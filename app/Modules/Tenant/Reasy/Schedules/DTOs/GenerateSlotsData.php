<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Schedules\DTOs;

use Spatie\LaravelData\Data;

class GenerateSlotsData extends Data
{
    public function __construct(
        public string $resource_id,
        public string $service_id,
        public string $date, // YYYY-MM-DD
    ) {}
}
