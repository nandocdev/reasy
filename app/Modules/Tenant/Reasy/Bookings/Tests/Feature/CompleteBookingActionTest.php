<?php

use App\Modules\Central\Provisioning\Models\Tenant;
use App\Modules\Tenant\Reasy\Bookings\Actions\CompleteBookingAction;
use App\Modules\Tenant\Reasy\Bookings\DTOs\CompleteBookingData;
use App\Modules\Tenant\Reasy\Bookings\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->tenant = Tenant::factory()->create();
    tenancy()->initialize($this->tenant);
});

it('completes a confirmed booking successfully', function () {
    $booking = Booking::factory()->create([
        'tenant_id' => $this->tenant->id,
        'status' => 'confirmed'
    ]);

    $data = new CompleteBookingData(
        booking_id: $booking->id,
        notes: 'Service rendered perfectly'
    );
    
    $action = app(CompleteBookingAction::class);
    $booking = $action->execute($data);

    expect($booking->status)->toBe('completed');
    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'completed'
    ]);
});

it('fails to complete if booking is not confirmed', function () {
    $booking = Booking::factory()->create([
        'tenant_id' => $this->tenant->id,
        'status' => 'draft'
    ]);

    $data = new CompleteBookingData(
        booking_id: $booking->id,
        notes: 'Attempted to complete draft'
    );
    
    $action = app(CompleteBookingAction::class);

    expect(fn() => $action->execute($data))->toThrow(\Exception::class);
});
