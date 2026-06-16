<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Bookings\Actions;

use App\Modules\Tenant\Reasy\Bookings\Models\Booking;
use App\Modules\Tenant\Reasy\Bookings\DTOs\ReserveSlotData;
use App\Modules\Tenant\Reasy\Resources\Models\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReserveSlotAction
{
    /**
     * [LISTA DE RIESGOS - RUP]
     * 1. [Técnico] Race condition (Doble-booking).
     *    Mitigación: Uso de SELECT FOR UPDATE sobre el recurso para bloquear el slot durante la validación y creación.
     * 2. [Técnico] Aislamiento RLS.
     *    Mitigación: Trait BelongsToTenant.
     */
    public function execute(ReserveSlotData $data): Booking
    {
        return DB::transaction(function () use ($data) {
            // 1. Lock the resource to prevent double-booking
            // Note: This assumes we are locking based on resource_id.
            // In a highly contended system, you might need a more granular lock on the slot.
            Resource::where('id', $data->resource_id)->lockForUpdate()->first();

            // 2. Validate availability (simple overlap check for now)
            $isOverlap = Booking::where('resource_id', $data->resource_id)
                ->where('status', 'confirmed')
                ->where(function ($query) use ($data) {
                    $query->whereBetween('start_time', [$data->start_time, $data->end_time])
                          ->orWhereBetween('end_time', [$data->start_time, $data->end_time]);
                })
                ->exists();

            if ($isOverlap) {
                throw new \Exception(__('This slot is no longer available.'));
            }

            // 3. Create booking
            return Booking::create([
                'location_id' => $data->location_id,
                'service_id' => $data->service_id,
                'resource_id' => $data->resource_id,
                'customer_id' => $data->customer_id,
                'start_time' => $data->start_time,
                'end_time' => $data->end_time,
                'timezone' => $data->timezone,
                'status' => 'draft', // Initial state
                'token' => Str::random(32),
                'price' => 0, // Placeholder
                'deposit_paid' => false,
                'currency' => 'USD',
                'customer_notes' => $data->customer_notes,
            ]);
        });
    }
}
