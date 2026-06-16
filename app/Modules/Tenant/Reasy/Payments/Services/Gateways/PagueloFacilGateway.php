<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Payments\Services\Gateways;

use App\Modules\Shared\Payments\Contracts\PaymentGateway;
use App\Modules\Shared\Payments\DTOs\MerchantData;
use App\Modules\Shared\Payments\DTOs\PaymentData;
use App\Modules\Shared\Payments\DTOs\PaymentResultData;
use App\Modules\Tenant\Reasy\Payments\DTOs\PaymentData as TenantPaymentData;

class PagueloFacilGateway implements PaymentGateway
{
    public function identifier(): string
    {
        return 'paguelo_facil';
    }

    public function loadMerchant(string $apiKey): MerchantData
    {
        // Implementación específica de PagueloFacil
        throw new \Exception("Not implemented yet");
    }

    public function buildCheckoutUrl(PaymentData $payment, string $apiKey): string
    {
        // Implementación específica de PagueloFacil
        throw new \Exception("Not implemented yet");
    }

    public function verifyWebhook(string $payload, string $signature, string $secret): bool
    {
        // Implementación específica de PagueloFacil
        return true;
    }

    public function parseWebhookPayload(array $payload): PaymentResultData
    {
        // Implementación específica de PagueloFacil
        return new PaymentResultData(success: true);
    }

    public function listTransactions(string $apiKey, array $filters = []): array
    {
        return [];
    }
}
