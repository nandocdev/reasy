<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Payments\Services\Gateways;

use App\Modules\Shared\Payments\Contracts\PaymentGateway;
use App\Modules\Shared\Payments\DTOs\MerchantData;
use App\Modules\Shared\Payments\DTOs\PaymentData;
use App\Modules\Shared\Payments\DTOs\PaymentResultData;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PagueloFacilGateway implements PaymentGateway
{
    public function identifier(): string
    {
        return 'paguelo_facil';
    }

    public function loadMerchant(string $apiKey): MerchantData
    {
        // Placeholder para la llamada a la API de PagueloFacil
        Log::info('Cargando comerciante desde PagueloFacil');
        throw new \Exception("Not implemented yet");
    }

    public function buildCheckoutUrl(PaymentData $payment, string $apiKey): string
    {
        // En un escenario real, aquí se llamaría a la API de PagueloFacil
        // para generar la sesión y obtener la URL.
        
        Log::info('Generando URL de checkout para PagueloFacil', ['order_id' => $payment->orderId]);

        // Simulación: en producción, esto devolvería la URL real del gateway
        return 'https://sandbox.paguelofacil.com/checkout/' . $payment->orderId;
    }

    public function verifyWebhook(string $payload, string $signature, string $secret): bool
    {
        // Implementación de verificación HMAC-SHA256
        $expectedSignature = hash_hmac('sha256', $payload, $secret);
        
        return hash_equals($expectedSignature, $signature);
    }

    public function parseWebhookPayload(array $payload): PaymentResultData
    {
        // Parseo específico según la estructura de JSON de PagueloFacil
        return new PaymentResultData(
            success: ($payload['status'] ?? '') === 'approved',
            transactionId: $payload['transaction_id'] ?? null,
            message: $payload['message'] ?? null
        );
    }

    public function listTransactions(string $apiKey, array $filters = []): array
    {
        return [];
    }
}
