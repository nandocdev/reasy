<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Payments\Http\Controllers;

use App\Modules\Tenant\Reasy\Bookings\Actions\ConfirmBookingAction;
use App\Modules\Tenant\Reasy\Bookings\DTOs\ConfirmBookingData;
use App\Modules\Tenant\Reasy\Payments\Services\PaymentGatewayFactory;
use App\Modules\Tenant\Settings\Models\TenantSetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function __construct(
        protected PaymentGatewayFactory $gatewayFactory
    ) {}

    public function handle(string $tenantId, string $gatewayIdentifier, Request $request, ConfirmBookingAction $confirmBookingAction)
    {
        $settings = TenantSetting::where('tenant_id', $tenantId)->firstOrFail();
        $gateway = $this->gatewayFactory->make($gatewayIdentifier);
        
        $secret = match ($gatewayIdentifier) {
            'paguelo_facil' => $settings->paguelo_facil_webhook_secret,
            default => throw new \Exception(__('Gateway not supported.')),
        };

        if (!$gateway->verifyWebhook($request->getContent(), $request->header('X-Signature', ''), $secret)) {
            Log::warning('Webhook signature verification failed', ['tenant_id' => $tenantId]);
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $result = $gateway->parseWebhookPayload($request->all());

        if ($result->success && $result->transactionId) {
            // Find booking by orderId (which we stored as booking id in PaymentData)
            // Note: Ensure your gateway integration correctly returns this or find it by other means
            $bookingId = $request->input('order_id'); 
            
            $confirmBookingAction->execute(new ConfirmBookingData(booking_id: $bookingId));
            Log::info('Booking confirmed via webhook', ['booking_id' => $bookingId]);
        }

        return response()->json(['status' => 'success']);
    }
}
