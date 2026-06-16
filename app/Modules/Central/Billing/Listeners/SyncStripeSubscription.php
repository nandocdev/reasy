<?php

declare(strict_types=1);

namespace App\Modules\Central\Billing\Listeners;

use App\Modules\Central\Billing\Models\Plan;
use App\Modules\Central\Provisioning\Models\Tenant;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;

/**
 * [CASO DE USO] Sincronizar Plan desde Stripe
 * Escucha los webhooks de Stripe procesados por Cashier para asegurar que tenants.plan_id esté actualizado.
 */
class SyncStripeSubscription
{
    public function handle(WebhookReceived $event): void
    {
        $payload = $event->payload;
        $type = $payload['type'] ?? null;

        // Solo nos interesan eventos de creación o actualización de suscripciones
        if (!in_array($type, ['customer.subscription.created', 'customer.subscription.updated'])) {
            return;
        }

        $stripeCustomerId = $payload['data']['object']['customer'] ?? null;
        $stripePlanId = $payload['data']['object']['items']['data'][0]['price']['id'] ?? null;

        if (!$stripeCustomerId || !$stripePlanId) {
            return;
        }

        try {
            $tenant = Tenant::where('stripe_id', $stripeCustomerId)->first();
            
            if (!$tenant) {
                Log::warning("Stripe Webhook: No se encontró Tenant para stripe_id [{$stripeCustomerId}]");
                return;
            }

            $plan = Plan::where('provider_plan_id', $stripePlanId)->first();

            if ($plan && $tenant->plan_id !== $plan->slug) {
                $tenant->update(['plan_id' => $plan->slug]);
                Log::info("Sincronizado plan [{$plan->slug}] para Tenant [{$tenant->id}] desde Webhook de Stripe");
            }

        } catch (\Exception $e) {
            Log::error("Error sincronizando plan de Stripe: " . $e->getMessage());
        }
    }
}
