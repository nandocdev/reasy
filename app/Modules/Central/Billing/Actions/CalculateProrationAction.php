<?php

declare(strict_types=1);

namespace App\Modules\Central\Billing\Actions;

use App\Modules\Central\Billing\Models\Subscription;
use Carbon\Carbon;

/**
 * [CASO DE USO] Calcular Prorrateo
 * Calcula los días restantes de una suscripción actual para aplicar créditos en el nuevo plan.
 */
final readonly class CalculateProrationAction
{
    public function execute(string $tenantId): array
    {
        $subscription = Subscription::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->first();

        if (!$subscription || !$subscription->current_period_end) {
            return ['remaining_days' => 0, 'credit_amount' => 0];
        }

        $now = now();
        $end = Carbon::instance($subscription->current_period_end);

        if ($now->gt($end)) {
            return ['remaining_days' => 0, 'credit_amount' => 0];
        }

        $remainingDays = $now->diffInDays($end);
        
        // Aquí se podría calcular el monto exacto basado en el precio del plan
        // Por ahora devolvemos los días para que el BillingManager tome la decisión
        return [
            'remaining_days' => (int) $remainingDays,
            'expiry_date' => $end,
        ];
    }
}
