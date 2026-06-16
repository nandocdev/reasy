<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Schedules\Actions;

use App\Modules\Tenant\Reasy\Schedules\DTOs\ManualBlockData;
use App\Modules\Tenant\Reasy\Schedules\Models\ManualBlock;
use Illuminate\Support\Facades\DB;

class CreateManualBlockAction
{
    /**
     * [LISTA DE RIESGOS - RUP]
     * 1. [Técnico] Superposición de bloqueos (Mitigación: Validación de solapamiento opcional o informativa).
     * 2. [Arquitectónico] Aislamiento de Tenant (Mitigación: Uso de BelongsToTenant trait y RLS).
     */
    public function execute(ManualBlockData $data): ManualBlock
    {
        return DB::transaction(function () use ($data) {
            return ManualBlock::create([
                'resource_id' => $data->resource_id,
                'start_time' => $data->start_time,
                'end_time' => $data->end_time,
                'reason' => $data->reason,
                'meta' => $data->meta,
            ]);
        });
    }
}
