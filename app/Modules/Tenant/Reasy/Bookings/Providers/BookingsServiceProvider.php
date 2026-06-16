<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Bookings\Providers;

use Illuminate\Support\ServiceProvider;

class BookingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../UI', 'bookings-tenant');
    }
}
