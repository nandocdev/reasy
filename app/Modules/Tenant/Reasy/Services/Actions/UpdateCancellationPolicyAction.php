<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Actions;

use App\Modules\Tenant\Reasy\Services\DTOs\CancellationPolicyData;
use App\Modules\Tenant\Reasy\Services\Models\CancellationPolicy;
use Illuminate\Support\Facades\DB;

class UpdateCancellationPolicyAction
{
    public function execute(CancellationPolicy $policy, CancellationPolicyData $data): CancellationPolicy
    {
        return DB::transaction(function () use ($policy, $data) {
            if ($data->is_default && !$policy->is_default) {
                CancellationPolicy::where('is_default', true)->update(['is_default' => false]);
            }

            $policy->update([
                'name' => $data->name,
                'rules' => $data->rules,
                'is_default' => $data->is_default,
            ]);

            return $policy;
        });
    }
}
