<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Resources\Actions;

use App\Modules\Tenant\Reasy\Resources\DTOs\ResourceData;
use App\Modules\Tenant\Reasy\Resources\Models\Resource;

class RegisterResourceAction
{
    public function execute(ResourceData $data): Resource
    {
        return Resource::create($data->toArray());
    }
}
