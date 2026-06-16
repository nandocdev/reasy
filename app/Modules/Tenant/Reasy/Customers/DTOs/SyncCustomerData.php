<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Customers\DTOs;

use Spatie\LaravelData\Data;

class SyncCustomerData extends Data
{
    public function __construct(
        public string $name,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $birth_date = null,
        public ?array $meta = null,
    ) {}
}
