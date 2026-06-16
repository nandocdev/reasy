<?php

declare(strict_types=1);

namespace App\Modules\Central\Billing\Console\Commands;

use App\Modules\Central\Billing\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * [LISTA DE RIESGOS - RUP]
 * 1. [Técnico] Procesamiento masivo puede agotar memoria (Mitigación: Uso de chunk() en implementaciones de alta escala).
 * 2. [Negocio] Suspensión errónea de clientes (Mitigación: Filtro explícito de gateway != stripe).
 */
class CheckExpirationsCommand extends Command
{
    protected $signature = 'billing:check-expirations';
    protected $description = 'Identify manual subscriptions that have expired and update their status.';

    public function handle(): int
    {
        $this->info('Checking for expired manual subscriptions...');

        $expiredSubscriptions = Subscription::where('status', 'active')
            ->where('gateway', '!=', 'stripe')
            ->whereNotNull('current_period_end')
            ->where('current_period_end', '<', Carbon::now())
            ->get();

        if ($expiredSubscriptions->isEmpty()) {
            $this->info('No expired manual subscriptions found.');
            return Command::SUCCESS;
        }

        $count = 0;
        foreach ($expiredSubscriptions as $subscription) {
            $subscription->update(['status' => 'past_due']);
            Log::info("Subscription {$subscription->id} for tenant {$subscription->tenant_id} marked as past_due due to expiration.");
            $count++;
        }

        $this->info("Updated {$count} subscriptions to 'past_due' status.");
        return Command::SUCCESS;
    }
}
