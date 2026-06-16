<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Locations\Actions;

use App\Modules\Tenant\Reasy\Locations\Models\Location;

class SwitchLocationStatusAction
{
    public function execute(Location $location): Location
    {
        $location->update([
            'is_active' => ! $location->is_active,
        ]);

        return $location->fresh();
    }
}
