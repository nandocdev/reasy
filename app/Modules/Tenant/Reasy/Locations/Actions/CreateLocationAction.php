<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Locations\Actions;

use App\Modules\Tenant\Reasy\Locations\DTOs\LocationData;
use App\Modules\Tenant\Reasy\Locations\Models\Location;
use Illuminate\Validation\ValidationException;

class CreateLocationAction
{
    public function execute(LocationData $data): Location
    {
        if (Location::where('slug', $data->slug)->exists()) {
            throw ValidationException::withMessages([
                'slug' => __('This slug is already in use for another location.'),
            ]);
        }

        return Location::create($data->toArray());
    }
}
