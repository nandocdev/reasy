<?php

declare(strict_types=1);

use App\Modules\Tenant\Reasy\Customers\Livewire\ManageCustomers;
use Illuminate\Support\Facades\Route;

Route::prefix('customers')->name('customers.')->group(function () {
    Route::get('/', ManageCustomers::class)->name('index');
});
