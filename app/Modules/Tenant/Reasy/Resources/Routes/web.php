<?php

declare(strict_types=1);

use App\Modules\Tenant\Reasy\Resources\Livewire\ResourceList;
use App\Modules\Tenant\Reasy\Resources\Livewire\ManageResource;
use Illuminate\Support\Facades\Route;

Route::get('/resources', ResourceList::class)->name('resources.index');
Route::get('/resources/create', ManageResource::class)->name('resources.create');
Route::get('/resources/{id}/edit', ManageResource::class)->name('resources.edit');
