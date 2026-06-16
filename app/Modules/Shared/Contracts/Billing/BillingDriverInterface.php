<?php

declare(strict_types=1);

namespace App\Modules\Shared\Contracts\Billing;

use App\Modules\Shared\Contracts\TenantContract;

interface BillingDriverInterface
{
    public function getSubscription(TenantContract $tenant): SubscriptionInterface;
}
