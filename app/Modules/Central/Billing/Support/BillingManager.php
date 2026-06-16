<?php

declare(strict_types=1);

namespace App\Modules\Central\Billing\Support;

use App\Modules\Central\Billing\Support\Drivers\InternalBillingProvider;
use App\Modules\Central\Billing\Support\Drivers\StripeBillingProvider;
use App\Modules\Shared\Services\Billing\BillingManager as BaseBillingManager;

class BillingManager extends BaseBillingManager
{
    public function getDefaultDriver(): string
    {
        return config('payments.default', 'clave');
    }

    public function createPaguelofacilDriver(): InternalBillingProvider
    {
        return $this->container->make(InternalBillingProvider::class);
    }

    public function createStripeDriver(): StripeBillingProvider
    {
        return $this->container->make(StripeBillingProvider::class);
    }

    public function createDlocalDriver(): InternalBillingProvider
    {
        return $this->container->make(InternalBillingProvider::class);
    }

    public function createClaveDriver(): InternalBillingProvider
    {
        return $this->createPaguelofacilDriver();
    }

    public function createManualDriver(): InternalBillingProvider
    {
        return $this->createPaguelofacilDriver();
    }

    /**
     * Get a unified list of invoices for the tenant.
     * Overrides the default driver-specific behavior to provide a consolidated view from the local database.
     */
    public function getInvoices(\App\Modules\Shared\Contracts\TenantContract $tenant): array
    {
        return \App\Modules\Central\Billing\Models\Invoice::where('tenant_id', $tenant->getId())
            ->latest('issued_at')
            ->get()
            ->all();
    }
}
