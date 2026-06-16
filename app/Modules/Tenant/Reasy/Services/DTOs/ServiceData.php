<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\DTOs;

use Spatie\LaravelData\Data;

class ServiceData extends Data
{
    public function __construct(
        public string $name,
        public int $duration,
        public int $price,
        public ?string $slug = null,
        public ?string $category_id = null,
        public ?string $cancellation_policy_id = null,
        public ?string $description = null,
        public int $buffer_before = 0,
        public int $buffer_after = 0,
        public string $currency = 'USD',
        public string $deposit_type = 'none',
        public int $deposit_value = 0,
        public int $capacity = 1,
        public bool $is_active = true,
        public bool $visible_on_widget = true,
        public int $order = 0,
    ) {}
}
