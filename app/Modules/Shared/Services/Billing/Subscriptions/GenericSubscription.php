<?php

declare(strict_types=1);

namespace App\Modules\Shared\Services\Billing\Subscriptions;

use App\Modules\Shared\Contracts\Billing\SubscriptionInterface;
use DateTimeInterface;

class GenericSubscription implements SubscriptionInterface
{
    public function __construct(
        protected bool $isActive,
        protected string $plan,
        protected ?DateTimeInterface $expiryDate,
        protected string $status,
        protected string $provider,
        protected bool $onGracePeriod = false,
        protected bool $isCancelled = false,
    ) {}

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getPlan(): string
    {
        return $this->plan;
    }

    public function getExpiryDate(): ?DateTimeInterface
    {
        return $this->expiryDate;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function onGracePeriod(): bool
    {
        return $this->onGracePeriod;
    }

    public function isCancelled(): bool
    {
        return $this->isCancelled;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }
}
