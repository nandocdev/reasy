<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Customers\Providers;

use Illuminate\Support\ServiceProvider;

class CustomersServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../UI', 'customers-tenant');
    }
}
