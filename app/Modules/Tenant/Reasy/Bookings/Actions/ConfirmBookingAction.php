<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Bookings\Actions;

use App\Modules\Tenant\Reasy\Bookings\Models\Booking;
use App\Modules\Tenant\Reasy\Bookings\DTOs\ConfirmBookingData;
use Illuminate\Support\Facades\DB;

class ConfirmBookingAction
{
    /**
     * [LISTA DE RIESGOS - RUP]
     * 1. [Técnico] Validación de pago insuficiente (Mitigación: Integración estricta con Payments module).
     * 2. [Técnico] Race condition al confirmar (Mitigación: lockForUpdate en el Booking).
     */
    public function execute(ConfirmBookingData $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $booking = Booking::lockForUpdate()->findOrFail($data->booking_id);
            
            if ($booking->status !== 'draft') {
                throw new \Exception(__('Booking cannot be confirmed. Current status: ') . $booking->status);
            }

            // Verify deposit if required
            $service = $booking->service;
            if ($service && $service->deposit_type !== 'none' && !$booking->deposit_paid) {
                throw new \Exception(__('Deposit required for this booking.'));
            }

            $booking->update(['status' => 'confirmed']);
            return $booking;
        });
    }
}
