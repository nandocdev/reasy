<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Customers\Actions;

use App\Modules\Tenant\Reasy\Customers\Models\Customer;
use App\Modules\Tenant\Reasy\Customers\DTOs\SyncCustomerData;
use Illuminate\Support\Facades\DB;

class SyncCustomerAction
{
    /**
     * [LISTA DE RIESGOS - RUP]
     * 1. [Técnico] Duplicidad de clientes (Mitigación: Upsert basado en email/teléfono).
     */
    public function execute(SyncCustomerData $data): Customer
    {
        return DB::transaction(function () use ($data) {
            // Basic upsert logic
            return Customer::updateOrCreate(
                ['email' => $data->email],
                [
                    'name' => $data->name,
                    'phone' => $data->phone,
                    'birth_date' => $data->birth_date,
                    'meta' => $data->meta,
                ]
            );
        });
    }
}
