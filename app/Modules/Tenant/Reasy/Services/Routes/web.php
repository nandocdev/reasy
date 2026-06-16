<?php

declare(strict_types=1);

use App\Modules\Tenant\Reasy\Services\Livewire\CancellationPolicyList;
use App\Modules\Tenant\Reasy\Services\Livewire\ManageCancellationPolicy;
use App\Modules\Tenant\Reasy\Services\Livewire\ManageService;
use App\Modules\Tenant\Reasy\Services\Livewire\ManageServiceCategory;
use App\Modules\Tenant\Reasy\Services\Livewire\ServiceCategoryList;
use App\Modules\Tenant\Reasy\Services\Livewire\ServiceList;
use Illuminate\Support\Facades\Route;

// Services
Route::get('services', ServiceList::class)->name('services.index');
Route::get('services/create', ManageService::class)->name('services.create');
Route::get('services/{id}/edit', ManageService::class)->name('services.edit');

// Service Categories
Route::get('service-categories', ServiceCategoryList::class)->name('service-categories.index');
Route::get('service-categories/create', ManageServiceCategory::class)->name('service-categories.create');
Route::get('service-categories/{id}/edit', ManageServiceCategory::class)->name('service-categories.edit');

// Cancellation Policies
Route::get('cancellation-policies', CancellationPolicyList::class)->name('cancellation-policies.index');
Route::get('cancellation-policies/create', ManageCancellationPolicy::class)->name('cancellation-policies.create');
Route::get('cancellation-policies/{id}/edit', ManageCancellationPolicy::class)->name('cancellation-policies.edit');
