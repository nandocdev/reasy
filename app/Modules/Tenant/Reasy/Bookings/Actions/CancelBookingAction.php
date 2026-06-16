<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Bookings\Actions;

use App\Modules\Tenant\Reasy\Bookings\Models\Booking;
use App\Modules\Tenant\Reasy\Bookings\DTOs\CancelBookingData;
use Illuminate\Support\Facades\DB;

class CancelBookingAction
{
    /**
     * [LISTA DE RIESGOS - RUP]
     * 1. [Técnico] Inconsistencia en estados de reserva (Mitigación: Transacción atómica).
     * 2. [Técnico] Falla en cálculo de reembolso (Mitigación: Validación contra políticas de cancelación).
     */
    public function execute(CancelBookingData $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $booking = Booking::lockForUpdate()->findOrFail($data->booking_id);

            if (!in_array($booking->status, ['draft', 'confirmed'])) {
                throw new \Exception(__('Booking cannot be cancelled in current status: ') . $booking->status);
            }

            // TODO: Integración con Payments module para calcular y procesar reembolso
            // $refundAmount = $this->calculateRefund($booking);
            // $this->processRefund($booking, $refundAmount);

            $booking->update([
                'status' => 'cancelled',
                'admin_notes' => ($booking->admin_notes ?? '') . "\n" . __('Cancellation Reason: ') . $data->reason,
            ]);

            return $booking;
        });
    }
}
