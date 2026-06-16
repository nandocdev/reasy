<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Bookings\Actions;

use App\Modules\Tenant\Reasy\Bookings\Models\Booking;
use App\Modules\Tenant\Reasy\Bookings\DTOs\CompleteBookingData;
use Illuminate\Support\Facades\DB;

class CompleteBookingAction
{
    /**
     * [LISTA DE RIESGOS - RUP]
     * 1. [Técnico] Inconsistencia en estados de reserva (Mitigación: Transacción atómica).
     */
    public function execute(CompleteBookingData $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $booking = Booking::lockForUpdate()->findOrFail($data->booking_id);

            if ($booking->status !== 'confirmed') {
                throw new \Exception(__('Booking cannot be completed in current status: ') . $booking->status);
            }

            $booking->update([
                'status' => 'completed',
                'admin_notes' => ($booking->admin_notes ?? '') . "\n" . __('Completion notes: ') . ($data->notes ?? __('No notes provided.')),
            ]);

            // TODO: Integración con Payments module para reconciliación final si aplica
            // TODO: Integración con Customers module para actualizar historial de éxito

            return $booking;
        });
    }
}
