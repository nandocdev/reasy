<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Payments\Actions;

use App\Modules\Tenant\Reasy\Bookings\Models\Booking;
use App\Modules\Tenant\Reasy\Payments\Services\PaymentGatewayFactory;
use App\Modules\Tenant\Reasy\Payments\DTOs\PaymentData;
use App\Modules\Tenant\Settings\Models\TenantSetting;
use Illuminate\Support\Facades\Log;

class ProcessDepositAction
{
    public function __construct(
        protected PaymentGatewayFactory $gatewayFactory
    ) {}

    /**
     * [LISTA DE RIESGOS - RUP]
     * 1. [Técnico] Falla en la integración con el gateway (Mitigación: Logging y manejo de excepciones).
     */
    public function execute(Booking $booking, string $gatewayIdentifier): string
    {
        $gateway = $this->gatewayFactory->make($gatewayIdentifier);
        
        $settings = TenantSetting::where('tenant_id', $booking->tenant_id)->firstOrFail();
        
        // Asumiendo que usamos la clave de PagueLoFacil
        $apiKey = $settings->paguelo_facil_api_key;
        
        if (!$apiKey) {
            throw new \Exception(__('Payment gateway not configured for this tenant.'));
        }

        $paymentData = new PaymentData(
            amount: $booking->price * 0.2, // Assuming 20% deposit
            description: 'Deposit for booking ' . $booking->id,
            returnUrl: route('tenant.reasy.bookings.confirm', ['id' => $booking->id]),
            orderId: $booking->id
        );

        try {
            return $gateway->buildCheckoutUrl($paymentData, $apiKey);
        } catch (\Exception $e) {
            Log::error('Payment gateway error: ' . $e->getMessage());
            throw new \Exception(__('Unable to process payment at this time.'));
        }
    }
}
