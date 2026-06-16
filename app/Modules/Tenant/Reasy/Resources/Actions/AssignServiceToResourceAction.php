<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Resources\Actions;

use App\Modules\Tenant\Reasy\Resources\Models\Resource;

class AssignServiceToResourceAction
{
    /**
     * @param array<string> $serviceIds Array of service UUIDs
     */
    public function execute(Resource $resource, array $serviceIds): Resource
    {
        $syncData = [];
        $tenantId = tenant()->id;

        foreach ($serviceIds as $id) {
            $syncData[$id] = ['tenant_id' => $tenantId];
        }

        $resource->services()->sync($syncData);

        return $resource->refresh();
    }
}
