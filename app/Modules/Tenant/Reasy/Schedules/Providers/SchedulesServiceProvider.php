<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Schedules\Providers;

use Illuminate\Support\ServiceProvider;

class SchedulesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register any module-specific services or bindings
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../UI', 'schedules-tenant');
    }
}
