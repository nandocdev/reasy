<?php

declare(strict_types=1);

namespace App\Modules\Shared\Tenancy\Http\Middleware;

use App\Modules\Central\Billing\Support\BillingManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * [LISTA DE RIESGOS - RUP]
 * 1. [Arquitectónico] Falso positivo de acceso si se confía solo en la sesión (Mitigación: Consulta directa vía BillingManager).
 * 2. [UX] Bloqueo total del usuario (Mitigación: Whitelist de rutas de facturación y soporte).
 */
class EnsureTenantHasActiveSubscription
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! function_exists('tenant') || ! tenant() || ! auth()->check()) {
            return $next($request);
        }

        if ($this->isWhitelisted($request)) {
            return $next($request);
        }

        if (tenant('plan_id') === 'free') {
            return $next($request);
        }

        try {
            $subscription = app(BillingManager::class)->getSubscription(tenant());

            if (! $subscription->isActive() && ! $subscription->onGracePeriod()) {
                return redirect()->route('tenant.billing.plans')
                    ->with('error', __('Your subscription is currently inactive. Please regularize your payment or choose a plan.'));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Subscription check error: " . $e->getMessage(), [
                'tenant_id' => tenant('id')
            ]);
        }

        return $next($request);
    }

    protected function isWhitelisted(Request $request): bool
    {
        return $request->routeIs([
            'tenant.billing.*',
            'tenant.settings.*',
            'tenant.support.*',
            'logout',
        ]) || $request->is('billing/*', 'settings/*', 'livewire/*', 'auth/*');
    }
}
