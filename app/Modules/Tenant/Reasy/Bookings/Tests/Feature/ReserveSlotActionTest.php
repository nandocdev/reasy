<?php

use App\Modules\Central\Provisioning\Models\Tenant;
use App\Modules\Tenant\Reasy\Bookings\Actions\ReserveSlotAction;
use App\Modules\Tenant\Reasy\Bookings\DTOs\ReserveSlotData;
use App\Modules\Tenant\Reasy\Bookings\Models\Booking;
use App\Modules\Tenant\Reasy\Customers\Models\Customer;
use App\Modules\Tenant\Reasy\Locations\Models\Location;
use App\Modules\Tenant\Reasy\Resources\Models\Resource;
use App\Modules\Tenant\Reasy\Services\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->tenant = Tenant::factory()->create();
    tenancy()->initialize($this->tenant);

    $this->location = Location::factory()->create(['tenant_id' => $this->tenant->id]);
    $this->service = Service::factory()->create(['tenant_id' => $this->tenant->id]);
    $this->resource = Resource::factory()->create(['tenant_id' => $this->tenant->id, 'location_id' => $this->location->id]);
    $this->customer = Customer::factory()->create(['tenant_id' => $this->tenant->id]);
});

it('reserves a slot successfully', function () {
    $data = new ReserveSlotData(
        location_id: $this->location->id,
        service_id: $this->service->id,
        resource_id: $this->resource->id,
        customer_id: $this->customer->id,
        start_time: '2026-06-16 10:00:00',
        end_time: '2026-06-16 11:00:00',
        timezone: 'UTC'
    );

    $action = app(ReserveSlotAction::class);
    $booking = $action->execute($data);

    expect($booking->status)->toBe('draft')
        ->and($booking->resource_id)->toBe($this->resource->id);
    
    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'draft'
    ]);
});

it('prevents double-booking for the same resource', function () {
    // Existing confirmed booking
    Booking::factory()->create([
        'tenant_id' => $this->tenant->id,
        'resource_id' => $this->resource->id,
        'start_time' => '2026-06-16 10:00:00',
        'end_time' => '2026-06-16 11:00:00',
        'status' => 'confirmed'
    ]);

    $data = new ReserveSlotData(
        location_id: $this->location->id,
        service_id: $this->service->id,
        resource_id: $this->resource->id,
        customer_id: $this->customer->id,
        start_time: '2026-06-16 10:30:00', // Overlaps
        end_time: '2026-06-16 11:30:00',
        timezone: 'UTC'
    );

    $action = app(ReserveSlotAction::class);

    expect(fn() => $action->execute($data))->toThrow(\Exception::class, __('This slot is no longer available.'));
});
