<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Providers;

use Illuminate\Support\ServiceProvider;

class ReasyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(\App\Modules\Tenant\Reasy\Locations\Providers\LocationsServiceProvider::class);
        $this->app->register(\App\Modules\Tenant\Reasy\Services\Providers\ServicesServiceProvider::class);
        $this->app->register(\App\Modules\Tenant\Reasy\Resources\Providers\ResourcesServiceProvider::class);
        $this->app->register(\App\Modules\Tenant\Reasy\Schedules\Providers\SchedulesServiceProvider::class);
        $this->app->register(\App\Modules\Tenant\Reasy\Bookings\Providers\BookingsServiceProvider::class);
    }

    public function boot(): void
    {
        // Common boot logic for Reasy product
    }
}
