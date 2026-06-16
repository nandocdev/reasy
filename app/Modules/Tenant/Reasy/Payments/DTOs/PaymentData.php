<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Payments\DTOs;

use Spatie\LaravelData\Data;

final class PaymentData extends Data
{
    public function __construct(
        public readonly float $amount,
        public readonly string $description,
        public readonly string $returnUrl,
        public readonly ?string $orderId = null,
    ) {}
}
