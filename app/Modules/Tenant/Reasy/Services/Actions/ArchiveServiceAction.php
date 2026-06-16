<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Actions;

use App\Modules\Tenant\Reasy\Services\Models\Service;

class ArchiveServiceAction
{
    public function execute(Service $service): bool
    {
        return $service->delete();
    }
}
