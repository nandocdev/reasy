<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Payments\Providers;

use App\Modules\Tenant\Reasy\Payments\Services\PaymentGatewayFactory;
use App\Modules\Tenant\Reasy\Payments\Services\Gateways\PagueloFacilGateway;
use Illuminate\Support\ServiceProvider;

class PaymentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentGatewayFactory::class, function () {
            $factory = new PaymentGatewayFactory();
            
            // Register supported gateways
            $factory->register('paguelo_facil', new PagueloFacilGateway());
            
            return $factory;
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../UI', 'payments-tenant');
    }
}
