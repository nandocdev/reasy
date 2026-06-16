<?php

declare(strict_types=1);

use App\Modules\Tenant\Reasy\Schedules\Livewire\ManageAvailability;
use Illuminate\Support\Facades\Route;

Route::get('/schedules', ManageAvailability::class)->name('schedules.index');
