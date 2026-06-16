<?php

use App\Modules\Central\Provisioning\Models\Tenant;
use App\Modules\Tenant\Reasy\Bookings\Actions\CancelBookingAction;
use App\Modules\Tenant\Reasy\Bookings\DTOs\CancelBookingData;
use App\Modules\Tenant\Reasy\Bookings\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->tenant = Tenant::factory()->create();
    tenancy()->initialize($this->tenant);
});

it('cancels a confirmed booking successfully', function () {
    $booking = Booking::factory()->create([
        'tenant_id' => $this->tenant->id,
        'status' => 'confirmed'
    ]);

    $data = new CancelBookingData(
        booking_id: $booking->id,
        reason: 'Client requested cancellation'
    );
    
    $action = app(CancelBookingAction::class);
    $booking = $action->execute($data);

    expect($booking->status)->toBe('cancelled');
    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'cancelled'
    ]);
});

it('fails to cancel an already cancelled booking', function () {
    $booking = Booking::factory()->create([
        'tenant_id' => $this->tenant->id,
        'status' => 'cancelled'
    ]);

    $data = new CancelBookingData(
        booking_id: $booking->id,
        reason: 'Try cancelling again'
    );
    
    $action = app(CancelBookingAction::class);

    expect(fn() => $action->execute($data))->toThrow(\Exception::class);
});
