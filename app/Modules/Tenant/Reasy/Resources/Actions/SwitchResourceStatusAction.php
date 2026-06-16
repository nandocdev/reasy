<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Resources\Actions;

use App\Modules\Tenant\Reasy\Resources\Models\Resource;

class SwitchResourceStatusAction
{
    public function execute(Resource $resource): Resource
    {
        $resource->update([
            'is_active' => !$resource->is_active,
        ]);

        return $resource->refresh();
    }
}
