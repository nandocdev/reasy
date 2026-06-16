<?php

use App\Modules\Central\Provisioning\Models\Tenant;
use App\Modules\Tenant\Reasy\Bookings\Actions\ConfirmBookingAction;
use App\Modules\Tenant\Reasy\Bookings\DTOs\ConfirmBookingData;
use App\Modules\Tenant\Reasy\Bookings\Models\Booking;
use App\Modules\Tenant\Reasy\Services\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->tenant = Tenant::factory()->create();
    tenancy()->initialize($this->tenant);
});

it('confirms a draft booking successfully', function () {
    $booking = Booking::factory()->create([
        'tenant_id' => $this->tenant->id,
        'status' => 'draft'
    ]);

    $data = new ConfirmBookingData(booking_id: $booking->id);
    $action = app(ConfirmBookingAction::class);
    
    $booking = $action->execute($data);

    expect($booking->status)->toBe('confirmed');
    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'confirmed'
    ]);
});

it('fails to confirm if deposit is required but not paid', function () {
    $service = Service::factory()->create([
        'tenant_id' => $this->tenant->id,
        'deposit_type' => 'fixed',
    ]);
    
    $booking = Booking::factory()->create([
        'tenant_id' => $this->tenant->id,
        'status' => 'draft',
        'service_id' => $service->id,
        'deposit_paid' => false
    ]);

    $data = new ConfirmBookingData(booking_id: $booking->id);
    $action = app(ConfirmBookingAction::class);

    expect(fn() => $action->execute($data))->toThrow(\Exception::class, __('Deposit required for this booking.'));
});

it('fails to confirm if already confirmed', function () {
    $booking = Booking::factory()->create([
        'tenant_id' => $this->tenant->id,
        'status' => 'confirmed'
    ]);

    $data = new ConfirmBookingData(booking_id: $booking->id);
    $action = app(ConfirmBookingAction::class);

    expect(fn() => $action->execute($data))->toThrow(\Exception::class);
});
