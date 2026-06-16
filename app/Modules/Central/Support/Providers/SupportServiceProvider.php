<?php

declare(strict_types=1);

namespace App\Modules\Central\Support\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class SupportServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../UI', 'support');

        $this->app->booted(function () {
            \Illuminate\Support\Facades\Route::middleware(['web', 'auth:central'])
                ->group(function () {
                    \Illuminate\Support\Facades\Route::get('/central/support/broadcasts', \App\Modules\Central\Support\Livewire\BroadcastCenter::class)
                        ->name('central.support.broadcasts');
                    
                    \Illuminate\Support\Facades\Route::get('/central/support/audit-log', \App\Modules\Central\Support\Livewire\CentralAuditLog::class)
                        ->name('central.support.audit-log');

                    \Illuminate\Support\Facades\Route::get('/central/support/tenants', \App\Modules\Central\Support\Livewire\TenantSupportPortal::class)
                        ->name('central.support.tenants');
                });
        });

        \Livewire\Livewire::component('support-broadcast-center', \App\Modules\Central\Support\Livewire\BroadcastCenter::class);
        \Livewire\Livewire::component('support-central-audit-log', \App\Modules\Central\Support\Livewire\CentralAuditLog::class);
        \Livewire\Livewire::component('support-tenant-portal', \App\Modules\Central\Support\Livewire\TenantSupportPortal::class);
        \Livewire\Livewire::component('tenant-support-bitacora', \App\Modules\Central\Support\Livewire\TenantSupportBitacora::class);
        \Livewire\Livewire::component('global-announcements', \App\Modules\Central\Support\Livewire\GlobalAnnouncements::class);
    }
}
