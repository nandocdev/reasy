<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Modules\Tenant\Reasy\Services\Livewire\ServiceList;
use App\Modules\Tenant\Reasy\Services\Livewire\ManageService;
use App\Modules\Tenant\Reasy\Services\Livewire\ServiceCategoryList;
use App\Modules\Tenant\Reasy\Services\Livewire\ManageServiceCategory;
use App\Modules\Tenant\Reasy\Services\Livewire\CancellationPolicyList;
use App\Modules\Tenant\Reasy\Services\Livewire\ManageCancellationPolicy;

class ServicesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../UI', 'services-tenant');

        Livewire::component('reasy-service-list', ServiceList::class);
        Livewire::component('reasy-manage-service', ManageService::class);
        Livewire::component('reasy-service-category-list', ServiceCategoryList::class);
        Livewire::component('reasy-manage-service-category', ManageServiceCategory::class);
        Livewire::component('reasy-cancellation-policy-list', CancellationPolicyList::class);
        Livewire::component('reasy-manage-cancellation-policy', ManageCancellationPolicy::class);
    }
}
