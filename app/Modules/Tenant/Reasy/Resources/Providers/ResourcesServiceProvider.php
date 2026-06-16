<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Resources\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Modules\Tenant\Reasy\Resources\Livewire\ResourceList;
use App\Modules\Tenant\Reasy\Resources\Livewire\ManageResource;

class ResourcesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../UI', 'resources-tenant');

        Livewire::component('reasy-resource-list', ResourceList::class);
        Livewire::component('reasy-manage-resource', ManageResource::class);
    }
}
