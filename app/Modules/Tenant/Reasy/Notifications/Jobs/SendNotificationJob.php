<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Notifications\Jobs;

use App\Modules\Tenant\Reasy\Notifications\DTOs\NotificationData;
use App\Modules\Tenant\Settings\Models\TenantSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly NotificationData $data
    ) {}

    public function handle(): void
    {
        $settings = TenantSetting::where('tenant_id', $this->data->tenant_id)->firstOrFail();

        // Configure tenant-specific SMTP
        Config::set('mail.mailers.smtp.host', $settings->smtp_host);
        Config::set('mail.mailers.smtp.port', $settings->smtp_port);
        Config::set('mail.mailers.smtp.username', $settings->smtp_user);
        Config::set('mail.mailers.smtp.password', $settings->smtp_password);
        Config::set('mail.from.address', $settings->smtp_from_email);
        Config::set('mail.from.name', $settings->smtp_from_name);

        if ($this->data->channel === 'email') {
            // TODO: Implement actual Mail class
            // Mail::to($this->data->recipient_email)->send(new DynamicNotification($this->data));
        }
    }
}
