<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Notifications\Actions;

use App\Modules\Tenant\Reasy\Notifications\DTOs\NotificationData;
use App\Modules\Tenant\Reasy\Notifications\Jobs\SendNotificationJob;

class SendConfirmationEmailAction
{
    public function execute(NotificationData $data): void
    {
        SendNotificationJob::dispatch($data)->onQueue('notifications');
    }
}
