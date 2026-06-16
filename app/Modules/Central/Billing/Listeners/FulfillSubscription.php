<?php

declare(strict_types=1);

namespace App\Modules\Central\Billing\Listeners;

use App\Modules\Central\Billing\Actions\ActivateTenantPlanAction;
use App\Modules\Central\Payments\Events\PaymentApproved;
use Illuminate\Support\Facades\Log;

class FulfillSubscription {
    public function __construct(
        private ActivateTenantPlanAction $activatePlanAction
    ) {}

    /**
     * Handle the event.
     */
    public function handle(PaymentApproved $event): void {
        $payment = $event->payment;
        $result = $event->result;
        $metadata = $payment->attempts()->latest()->first()?->payload ?? [];

        // Check if this payment was for a subscription
        if (($metadata['customFieldValues']['type'] ?? '') !== 'subscription') {
            return;
        }

        try {
            $tenantId = $metadata['customFieldValues']['tenant_id'] ?? $payment->tenant_id;
            $planId = $metadata['customFieldValues']['plan_id'] ?? null;

            if (!$planId) {
                Log::error("Subscription fulfillment failed: Plan ID missing in metadata", ['payment' => $payment->id]);
                return;
            }

            $this->activatePlanAction->execute(
                tenantId: $tenantId,
                planId: $planId,
                provider: 'paguelofacil',
                providerSubscriptionId: $result->gatewayReference
            );

        } catch (\Exception $e) {
            Log::error("Error fulfilling subscription from approved payment: " . $e->getMessage());
        }
    }
}
