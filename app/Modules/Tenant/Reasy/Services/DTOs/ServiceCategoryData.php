<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\DTOs;

use Spatie\LaravelData\Data;

class ServiceCategoryData extends Data
{
    public function __construct(
        public string $name,
        public ?string $slug = null,
        public ?string $description = null,
        public int $order = 0,
    ) {}
}
