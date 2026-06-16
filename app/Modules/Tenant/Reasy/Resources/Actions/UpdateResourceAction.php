<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Resources\Actions;

use App\Modules\Tenant\Reasy\Resources\DTOs\ResourceData;
use App\Modules\Tenant\Reasy\Resources\Models\Resource;

class UpdateResourceAction
{
    public function execute(Resource $resource, ResourceData $data): Resource
    {
        $resource->update($data->toArray());

        return $resource->refresh();
    }
}
