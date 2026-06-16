<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\DTOs;

use Spatie\LaravelData\Data;

class CancellationPolicyData extends Data
{
    public function __construct(
        public string $name,
        /** @var array<int, array{before_hours: int, refund_percent: int}> */
        public array $rules,
        public bool $is_default = false,
    ) {}
}
