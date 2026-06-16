<?php

declare(strict_types=1);

namespace App\Modules\Central\Billing\Actions;

use App\Modules\Central\Billing\Models\Plan;
use App\Modules\Central\Billing\Models\Subscription;
use App\Modules\Central\Provisioning\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * [CASO DE USO] Activar Plan de Tenant
 * Centraliza la lógica de activación de un plan, ya sea por pago manual o automático.
 */
final readonly class ActivateTenantPlanAction
{
    public function execute(string $tenantId, string $planId, string $provider, ?string $providerSubscriptionId = null): void
    {
        DB::transaction(function () use ($tenantId, $planId, $provider, $providerSubscriptionId) {
            $tenant = Tenant::findOrFail($tenantId);
            $plan = Plan::where('id', $planId)->orWhere('slug', $planId)->firstOrFail();

            // 1. Actualizar o crear registro de suscripción local
            Subscription::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'provider_subscription_id' => $providerSubscriptionId ?? "manual_{$tenant->id}",
                ],
                [
                    'plan_id' => $plan->id,
                    'status' => 'active',
                    'gateway' => $provider,
                    'current_period_end' => now()->addMonth(), // Por defecto mensual, ajustable por DTO si es necesario
                ]
            );

            // 2. Sincronizar el plan activo en el modelo Tenant para acceso rápido
            $tenant->update(['plan_id' => $plan->slug]);

            Log::info("Plan [{$plan->slug}] activado para el Tenant [{$tenant->id}] vía [{$provider}]");
        });
    }
}
