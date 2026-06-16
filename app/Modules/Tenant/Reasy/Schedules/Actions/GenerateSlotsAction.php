<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Schedules\Actions;

use App\Modules\Tenant\Reasy\Bookings\Models\Booking;
use App\Modules\Tenant\Reasy\Resources\Models\Resource;
use App\Modules\Tenant\Reasy\Schedules\DTOs\GenerateSlotsData;
use App\Modules\Tenant\Reasy\Schedules\Models\ManualBlock;
use App\Modules\Tenant\Reasy\Schedules\Models\Schedule;
use App\Modules\Tenant\Reasy\Schedules\Models\ScheduleException;
use App\Modules\Tenant\Reasy\Services\Models\Service;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class GenerateSlotsAction
{
    /**
     * [LISTA DE RIESGOS - RUP]
     * 1. [Técnico] Complejidad computacional en el cálculo de slots (Mitigación: Caché y algoritmos eficientes).
     * 2. [Técnico] Manejo de zonas horarias (Mitigación: Uso consistente de Carbon y zona horaria de la ubicación).
     * 3. [Arquitectónico] Race Condition (Mitigación: Este es un generador de lectura; el bloqueo real ocurre en ReserveSlotAction).
     */
    public function execute(GenerateSlotsData $data): Collection
    {
        $service = Service::findOrFail($data->service_id);
        $resource = Resource::with('location')->findOrFail($data->resource_id);
        $date = Carbon::parse($data->date);
        $dayOfWeek = $date->dayOfWeek;

        // 1. Obtener horario base del recurso
        $baseSchedules = Schedule::where('resource_id', $resource->id)
            ->where('day_of_week', $dayOfWeek)
            ->get();

        if ($baseSchedules->isEmpty()) {
            return collect();
        }

        // 2. Obtener excepciones y bloqueos manuales
        $exceptions = ScheduleException::where('resource_id', $resource->id)
            ->whereDate('date', $date)
            ->get();

        $manualBlocks = ManualBlock::where('resource_id', $resource->id)
            ->whereDate('start_time', $date)
            ->get();

        // 3. Obtener reservas existentes (no canceladas ni expiradas)
        $bookings = Booking::where('resource_id', $resource->id)
            ->whereDate('start_time', $date)
            ->whereNotIn('status', ['cancelled', 'expired'])
            ->get();

        $availableSlots = collect();
        $totalDuration = $service->duration + $service->buffer_before + $service->buffer_after;
        $granularity = 15; // Minutos

        foreach ($baseSchedules as $schedule) {
            $windowStart = $date->copy()->setTimeFromTimeString($schedule->start_time);
            $windowEnd = $date->copy()->setTimeFromTimeString($schedule->end_time);

            // Generar periodos de tiempo candidatos
            $period = CarbonPeriod::since($windowStart)
                ->minutes($granularity)
                ->until($windowEnd->copy()->subMinutes($service->duration));

            foreach ($period as $slotStart) {
                $slotEnd = $slotStart->copy()->addMinutes($service->duration);
                
                // El slot efectivo incluye los buffers para validación de disponibilidad
                $effectiveStart = $slotStart->copy()->subMinutes($service->buffer_before);
                $effectiveEnd = $slotEnd->copy()->addMinutes($service->buffer_after);

                if ($this->isAvailable($effectiveStart, $effectiveEnd, $exceptions, $manualBlocks, $bookings, $windowStart, $windowEnd)) {
                    $availableSlots->push([
                        'start_time' => $slotStart->toIso8601String(),
                        'end_time' => $slotEnd->toIso8601String(),
                        'resource_id' => $resource->id,
                        'service_id' => $service->id,
                    ]);
                }
            }
        }

        return $availableSlots;
    }

    private function isAvailable(
        \Carbon\CarbonInterface $start,
        \Carbon\CarbonInterface $end,
        Collection $exceptions,
        Collection $manualBlocks,
        Collection $bookings,
        \Carbon\CarbonInterface $windowStart,
        \Carbon\CarbonInterface $windowEnd
    ): bool {
        // Fuera del horario base (ajustado por buffers)
        if ($start->lt($windowStart) || $end->gt($windowEnd)) {
            return false;
        }

        // Verificar excepciones
        foreach ($exceptions as $exception) {
            if ($exception->is_unavailable) {
                $excStart = Carbon::instance($exception->date)->setTimeFromTimeString($exception->start_time ?? '00:00');
                $excEnd = Carbon::instance($exception->date)->setTimeFromTimeString($exception->end_time ?? '23:59');
                if ($this->overlaps($start, $end, $excStart, $excEnd)) {
                    return false;
                }
            }
        }

        // Verificar bloqueos manuales
        foreach ($manualBlocks as $block) {
            if ($this->overlaps($start, $end, $block->start_time, $block->end_time)) {
                return false;
            }
        }

        // Verificar reservas
        foreach ($bookings as $booking) {
            if ($this->overlaps($start, $end, $booking->start_time, $booking->end_time)) {
                return false;
            }
        }

        return true;
    }

    private function overlaps(\Carbon\CarbonInterface $start1, \Carbon\CarbonInterface $end1, \Carbon\CarbonInterface $start2, \Carbon\CarbonInterface $end2): bool
    {
        return $start1->lt($end2) && $end1->gt($start2);
    }
}
