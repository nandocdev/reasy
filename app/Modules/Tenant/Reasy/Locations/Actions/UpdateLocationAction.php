<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Locations\Actions;

use App\Modules\Tenant\Reasy\Locations\DTOs\LocationData;
use App\Modules\Tenant\Reasy\Locations\Models\Location;
use Illuminate\Validation\ValidationException;

class UpdateLocationAction
{
    public function execute(Location $location, LocationData $data): Location
    {
        if (Location::where('slug', $data->slug)->where('id', '!=', $location->id)->exists()) {
            throw ValidationException::withMessages([
                'slug' => __('This slug is already in use for another location.'),
            ]);
        }

        $location->update($data->toArray());

        return $location->fresh();
    }
}
