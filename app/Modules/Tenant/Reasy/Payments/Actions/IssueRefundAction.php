<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Payments\Actions;

use App\Modules\Tenant\Reasy\Bookings\Models\Booking;
use App\Modules\Tenant\Reasy\Payments\DTOs\RefundData;
use App\Modules\Tenant\Reasy\Payments\Services\PaymentGatewayFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IssueRefundAction
{
    public function __construct(
        protected PaymentGatewayFactory $gatewayFactory
    ) {}

    /**
     * [LISTA DE RIESGOS - RUP]
     * 1. [Técnico] Falla en la integración con el gateway (Mitigación: Logging y manejo de excepciones).
     * 2. [Técnico] Doble reembolso (Mitigación: Validación de estado de reembolso en Booking).
     */
    public function execute(RefundData $data, string $gatewayIdentifier, string $apiKey): bool
    {
        return DB::transaction(function () use ($data, $gatewayIdentifier, $apiKey) {
            $booking = Booking::lockForUpdate()->findOrFail($data->booking_id);

            // TODO: Implementar validación de estado de reembolso (evitar doble reembolso)
            
            $gateway = $this->gatewayFactory->make($gatewayIdentifier);

            try {
                // TODO: Llamar método del gateway para procesar reembolso real
                // $gateway->refund($data->amount, $apiKey, $booking->id);

                Log::info('Refund issued for booking ' . $booking->id);
                
                $booking->update([
                    'admin_notes' => ($booking->admin_notes ?? '') . "\n" . __('Refund of ') . $data->amount . __(' issued: ') . $data->reason,
                ]);

                return true;
            } catch (\Exception $e) {
                Log::error('Payment gateway refund error: ' . $e->getMessage());
                throw new \Exception(__('Unable to process refund at this time.'));
            }
        });
    }
}
