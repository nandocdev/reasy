<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Resources\DTOs;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\Min;

class ResourceData extends Data
{
    public function __construct(
        public string $location_id,
        public string $name,
        #[In(['staff', 'room', 'equipment'])]
        public string $type,
        public ?string $user_id = null,
        public ?string $description = null,
        public ?string $avatar_path = null,
        #[Min(1)]
        public int $capacity = 1,
        public bool $is_active = true,
        public ?array $meta = null,
    ) {}
}
