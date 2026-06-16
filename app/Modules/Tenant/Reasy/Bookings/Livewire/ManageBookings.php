<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Bookings\Livewire;

use App\Modules\Tenant\Reasy\Bookings\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;

class ManageBookings extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $bookings = Booking::where('tenant_id', tenant('id'))
            ->with(['customer', 'service', 'resource'])
            ->latest()
            ->paginate(15);

        return view('bookings-tenant::livewire.manage-bookings', [
            'bookings' => $bookings,
        ])->layout('layouts.app.sidebar');
    }
}
