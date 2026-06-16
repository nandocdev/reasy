<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Notifications\Jobs;

use App\Modules\Tenant\Reasy\Notifications\DTOs\NotificationData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly NotificationData $data
    ) {}

    public function handle(): void
    {
        // TODO: Implement actual dispatch logic to Mailer/SMS provider
    }
}
