<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Locations\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Modules\Tenant\Reasy\Locations\Livewire\LocationList;
use App\Modules\Tenant\Reasy\Locations\Livewire\ManageLocation;

class LocationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../UI', 'locations-tenant');

        Livewire::component('reasy-location-list', LocationList::class);
        Livewire::component('reasy-manage-location', ManageLocation::class);
    }
}
