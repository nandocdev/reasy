<?php

declare(strict_types=1);

namespace App\Modules\Shared\Payments\DTOs;

use Spatie\LaravelData\Data;

final class PaymentResultData extends Data
{
    public function __construct(
        public readonly bool $success,
        public readonly ?string $transactionId = null,
        public readonly ?string $message = null,
    ) {}
}
