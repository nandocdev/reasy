<?php

declare(strict_types=1);

namespace App\Modules\Central\Billing\Support\Drivers;

use App\Modules\Central\Billing\Actions\SyncInvoicesAction;
use App\Modules\Central\Billing\Support\PlanManager;
use App\Modules\Central\Provisioning\Models\Tenant;
use App\Modules\Shared\Contracts\BillingProvider;

use App\Modules\Shared\Contracts\Billing\SubscriptionInterface;
use App\Modules\Shared\Services\Billing\Subscriptions\GenericSubscription;

class StripeBillingProvider implements BillingProvider
{
    public function getSubscription(TenantContract $tenant): SubscriptionInterface
    {
        /** @var \App\Modules\Central\Provisioning\Models\Tenant $tenant */
        $subscription = $tenant->subscription('default');

        if (! $subscription) {
            return new GenericSubscription(
                isActive: false,
                plan: 'none',
                expiryDate: null,
                status: 'inactive',
                provider: 'stripe'
            );
        }

        return new GenericSubscription(
            isActive: $subscription->active(),
            plan: $tenant->plan_id ?? 'unknown',
            expiryDate: $subscription->ends_at,
            status: $subscription->stripe_status,
            provider: 'stripe',
            onGracePeriod: $subscription->onGracePeriod(),
            isCancelled: $subscription->canceled()
        );
    }

    public function createCheckoutSession(TenantContract $tenant, string $planId): string
    {
        $stripeId = PlanManager::getStripeId($planId);

        if (! $stripeId) {
            throw new \InvalidArgumentException("Plan [{$planId}] has no Stripe ID configured.");
        }

        $tenantDomain = $tenant->domains()->first()?->domain ?? $tenant->slug . '.' . config('tenancy.central_domain');
        $scheme = parse_url(config('app.url'), PHP_URL_SCHEME) ?? 'https';
        $port = parse_url(config('app.url'), PHP_URL_PORT);
        $portSuffix = $port ? ":$port" : '';
        $baseUrl = "$scheme://$tenantDomain$portSuffix";

        return $tenant->newSubscription('default', $stripeId)
            ->checkout([
                'success_url' => "$baseUrl/billing/success",
                'cancel_url' => "$baseUrl/billing/cancel",
            ])->url;
    }

    public function cancelSubscription(TenantContract $tenant, string $subscriptionId, bool $immediately = false): void
    {
        /** @var \App\Modules\Central\Provisioning\Models\Tenant $tenant */
        $subscription = $tenant->subscriptions()->where('stripe_id', $subscriptionId)->first();

        if (! $subscription) {
            return;
        }

        if ($immediately) {
            $subscription->cancelNow();
        } else {
            $subscription->cancel();
        }
    }

    public function syncSubscription(TenantContract $tenant): void
    {
        /** @var \App\Modules\Central\Provisioning\Models\Tenant $tenant */
        $tenant->updateStripeCustomer();
        
        $subscription = $tenant->subscription('default');
        if ($subscription) {
            $subscription->syncStripeStatus();
        }

        // Sync invoices as well
        app(SyncInvoicesAction::class)->execute($tenant);
    }

    public function getSubscriptionData(TenantContract $tenant, string $subscriptionId): ?array
    {
        /** @var \App\Modules\Central\Provisioning\Models\Tenant $tenant */
        $subscription = $tenant->subscriptions()->where('stripe_id', $subscriptionId)->first();

        if (! $subscription) {
            return null;
        }

        $stripeSubscription = $subscription->asStripeSubscription();

        return [
            'status' => $stripeSubscription->status,
            'current_period_end' => $stripeSubscription->current_period_end,
            'cancel_at_period_end' => $stripeSubscription->cancel_at_period_end,
        ];
    }

    public function getInvoices(TenantContract $tenant): array
    {
        /** @var \App\Modules\Central\Provisioning\Models\Tenant $tenant */
        return $tenant->invoices()->toArray();
    }
}
