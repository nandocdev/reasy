<?php

declare(strict_types=1);

use App\Modules\Tenant\Reasy\Locations\Livewire\LocationList;
use App\Modules\Tenant\Reasy\Locations\Livewire\ManageLocation;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web',
    'auth',
    \App\Modules\Tenant\Identity\Http\Middleware\EnsureUserBelongsToTenant::class,
])->prefix('admin/reasy')->name('tenant.reasy.')->group(function () {
    Route::get('/locations', LocationList::class)->name('locations.index');
    Route::get('/locations/create', ManageLocation::class)->name('locations.create');
    Route::get('/locations/{id}/edit', ManageLocation::class)->name('locations.edit');
});
