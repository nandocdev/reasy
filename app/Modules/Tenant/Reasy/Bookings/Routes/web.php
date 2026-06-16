<?php

declare(strict_types=1);

use App\Modules\Tenant\Reasy\Bookings\Livewire\ManageBookings;
use App\Modules\Tenant\Reasy\Bookings\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::prefix('bookings')->name('bookings.')->group(function () {
    // UI Routes
    Route::get('/', ManageBookings::class)->name('index');
    
    // API Routes (kept from before)
    Route::post('/reserve', [BookingController::class, 'reserve'])->name('reserve');
    Route::post('/{id}/confirm', [BookingController::class, 'confirm'])->name('confirm');
    Route::post('/{id}/cancel', [BookingController::class, 'cancel'])->name('cancel');
    Route::post('/{id}/complete', [BookingController::class, 'complete'])->name('complete');
    Route::post('/{id}/no-show', [BookingController::class, 'noShow'])->name('no-show');
});
