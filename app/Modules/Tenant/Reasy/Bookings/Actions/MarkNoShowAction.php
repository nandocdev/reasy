<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Bookings\Actions;

use App\Modules\Tenant\Reasy\Bookings\Models\Booking;
use App\Modules\Tenant\Reasy\Bookings\DTOs\MarkNoShowData;
use Illuminate\Support\Facades\DB;

class MarkNoShowAction
{
    /**
     * [LISTA DE RIESGOS - RUP]
     * 1. [Técnico] Inconsistencia en estados de reserva (Mitigación: Transacción atómica).
     * 2. [Técnico] Falla al actualizar métricas del cliente (Mitigación: Integración futura con Customers module).
     */
    public function execute(MarkNoShowData $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $booking = Booking::lockForUpdate()->findOrFail($data->booking_id);

            if ($booking->status !== 'confirmed') {
                throw new \Exception(__('Booking cannot be marked as no-show in current status: ') . $booking->status);
            }

            $booking->update([
                'status' => 'no_show',
                'admin_notes' => ($booking->admin_notes ?? '') . "\n" . __('No-show reason: ') . ($data->reason ?? __('No reason provided.')),
            ]);

            // TODO: Integración con Customers module para incrementar tasa de no-shows
            // TODO: Integración con Payments module para retener depósito si aplica

            return $booking;
        });
    }
}
