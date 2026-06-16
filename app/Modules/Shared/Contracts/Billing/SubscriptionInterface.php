<?php

declare(strict_types=1);

namespace App\Modules\Shared\Contracts\Billing;

use DateTimeInterface;

/**
 * Interface SubscriptionInterface
 * 
 * Abstraction for subscription state regardless of the provider (Stripe or Manual/PagueloFacil).
 * Part of Phase 1: Abstraction (Shared Context) - STRATEGY_UC_CE_02.
 */
interface SubscriptionInterface
{
    public function isActive(): bool;

    /**
     * Get the name or identifier of the plan.
     */
    public function getPlan(): string;

    public function getExpiryDate(): ?DateTimeInterface;

    public function getStatus(): string;

    public function onGracePeriod(): bool;

    public function isCancelled(): bool;

    /**
     * Get the provider name (e.g., 'stripe', 'manual').
     */
    public function getProvider(): string;
}
