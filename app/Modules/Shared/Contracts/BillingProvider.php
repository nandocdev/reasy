<?php

declare(strict_types=1);

namespace App\Modules\Shared\Contracts;

interface BillingProvider
{
    public function getSubscription(TenantContract $tenant): \App\Modules\Shared\Contracts\Billing\SubscriptionInterface;

    public function createCheckoutSession(TenantContract $tenant, string $planId): string;
    
    public function cancelSubscription(TenantContract $tenant, string $subscriptionId, bool $immediately = false): void;
    
    public function syncSubscription(TenantContract $tenant): void;

    public function getSubscriptionData(TenantContract $tenant, string $subscriptionId): ?array;
    
    public function getInvoices(TenantContract $tenant): array;
}
