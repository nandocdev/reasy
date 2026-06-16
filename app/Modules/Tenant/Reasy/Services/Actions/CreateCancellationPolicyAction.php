<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Actions;

use App\Modules\Tenant\Reasy\Services\DTOs\CancellationPolicyData;
use App\Modules\Tenant\Reasy\Services\Models\CancellationPolicy;
use Illuminate\Support\Facades\DB;

class CreateCancellationPolicyAction
{
    public function execute(CancellationPolicyData $data): CancellationPolicy
    {
        return DB::transaction(function () use ($data) {
            if ($data->is_default) {
                CancellationPolicy::where('is_default', true)->update(['is_default' => false]);
            }

            return CancellationPolicy::create([
                'name' => $data->name,
                'rules' => $data->rules,
                'is_default' => $data->is_default,
            ]);
        });
    }
}
