<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Payments\Services;

use App\Modules\Shared\Payments\Contracts\PaymentGateway;
use App\Modules\Tenant\Reasy\Payments\Services\Gateways\PagueloFacilGateway;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class PaymentGatewayFactory
{
    protected Collection $gateways;

    public function __construct()
    {
        $this->gateways = collect();
    }

    public function register(string $identifier, PaymentGateway $gateway): void
    {
        $this->gateways->put($identifier, $gateway);
    }

    public function make(string $identifier): PaymentGateway
    {
        if (!$this->gateways->has($identifier)) {
            throw new InvalidArgumentException("Gateway [{$identifier}] not supported.");
        }

        return $this->gateways->get($identifier);
    }
}
