<?php

declare(strict_types=1);

use App\Modules\Tenant\Reasy\Payments\Http\Controllers\PaymentWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('payments')->name('payments.')->group(function () {
    Route::post('/webhook/{tenantId}/{gatewayIdentifier}', [PaymentWebhookController::class, 'handle'])->name('webhook');
});
