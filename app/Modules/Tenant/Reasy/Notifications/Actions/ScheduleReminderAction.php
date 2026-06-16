<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Notifications\Actions;

use App\Modules\Tenant\Reasy\Notifications\DTOs\NotificationData;
use App\Modules\Tenant\Reasy\Notifications\Jobs\SendNotificationJob;

class ScheduleReminderAction
{
    public function execute(NotificationData $data, \DateTimeInterface $sendAt): void
    {
        SendNotificationJob::dispatch($data)
            ->delay($sendAt)
            ->onQueue('notifications');
    }
}
