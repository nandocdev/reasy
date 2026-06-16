<?php

use App\Modules\Central\Provisioning\Models\Tenant;
use App\Modules\Tenant\Reasy\Bookings\Actions\MarkNoShowAction;
use App\Modules\Tenant\Reasy\Bookings\DTOs\MarkNoShowData;
use App\Modules\Tenant\Reasy\Bookings\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->tenant = Tenant::factory()->create();
    tenancy()->initialize($this->tenant);
});

it('marks a confirmed booking as no-show successfully', function () {
    $booking = Booking::factory()->create([
        'tenant_id' => $this->tenant->id,
        'status' => 'confirmed'
    ]);

    $data = new MarkNoShowData(
        booking_id: $booking->id,
        reason: 'Client did not show up'
    );
    
    $action = app(MarkNoShowAction::class);
    $booking = $action->execute($data);

    expect($booking->status)->toBe('no_show');
    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'no_show'
    ]);
});

it('fails to mark as no-show if not confirmed', function () {
    $booking = Booking::factory()->create([
        'tenant_id' => $this->tenant->id,
        'status' => 'draft'
    ]);

    $data = new MarkNoShowData(
        booking_id: $booking->id,
        reason: 'Attempted to mark draft as no-show'
    );
    
    $action = app(MarkNoShowAction::class);

    expect(fn() => $action->execute($data))->toThrow(\Exception::class);
});
