<?php

declare(strict_types=1);

namespace App\Modules\Shared\Services\Billing;

use App\Modules\Shared\Contracts\Billing\SubscriptionInterface;
use App\Modules\Shared\Contracts\BillingProvider;
use App\Modules\Shared\Contracts\TenantContract;
use Illuminate\Support\Manager;

/**
 * Class BillingManager
 * 
 * Abstract manager for resolving billing providers.
 * Concrete drivers should be registered in the Central context.
 */
class BillingManager extends Manager implements BillingProvider
{
    public function getDefaultDriver(): string
    {
        return $this->config->get('billing.default', 'manual');
    }

    /**
     * Resolve the subscription state for a specific tenant.
     */
    public function getSubscription(TenantContract $tenant): SubscriptionInterface
    {
        return $this->forTenant($tenant)->getSubscription($tenant);
    }

    public function forTenant(TenantContract $tenant): BillingProvider
    {
        $driver = $this->resolveDriverForTenant($tenant);
        
        return $this->driver($driver);
    }

    protected function resolveDriverForTenant(TenantContract $tenant): string
    {
        // For Central context Tenant model which implements Billable (Stripe)
        if (method_exists($tenant, 'asStripeCustomer') && property_exists($tenant, 'stripe_id') && $tenant->stripe_id) {
            return 'stripe';
        }

        return 'manual'; 
    }

    // --- BillingProvider Implementation ---

    public function createCheckoutSession(TenantContract $tenant, string $planId): string
    {
        return $this->forTenant($tenant)->createCheckoutSession($tenant, $planId);
    }

    public function cancelSubscription(TenantContract $tenant, string $subscriptionId, bool $immediately = false): void
    {
        $this->forTenant($tenant)->cancelSubscription($tenant, $subscriptionId, $immediately);
    }

    public function syncSubscription(TenantContract $tenant): void
    {
        $this->forTenant($tenant)->syncSubscription($tenant);
    }

    public function getSubscriptionData(TenantContract $tenant, string $subscriptionId): ?array
    {
        return $this->forTenant($tenant)->getSubscriptionData($tenant, $subscriptionId);
    }

    public function getInvoices(TenantContract $tenant): array
    {
        return $this->forTenant($tenant)->getInvoices($tenant);
    }
}
