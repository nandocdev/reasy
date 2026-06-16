<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Notifications\DTOs;

use Spatie\LaravelData\Data;

final class NotificationData extends Data
{
    public function __construct(
        public readonly string $tenant_id,
        public readonly string $recipient_email,
        public readonly string $template_id,
        public readonly array $variables,
        public readonly string $channel = 'email',
    ) {}
}
