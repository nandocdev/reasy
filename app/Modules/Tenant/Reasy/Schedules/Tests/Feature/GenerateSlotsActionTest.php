<?php

use App\Modules\Central\Billing\Models\Plan;
use App\Modules\Central\Provisioning\Models\Tenant;
use App\Modules\Tenant\Reasy\Bookings\Models\Booking;
use App\Modules\Tenant\Reasy\Customers\Models\Customer;
use App\Modules\Tenant\Reasy\Locations\Models\Location;
use App\Modules\Tenant\Reasy\Resources\Models\Resource;
use App\Modules\Tenant\Reasy\Schedules\Actions\GenerateSlotsAction;
use App\Modules\Tenant\Reasy\Schedules\DTOs\GenerateSlotsData;
use App\Modules\Tenant\Reasy\Schedules\Models\Schedule;
use App\Modules\Tenant\Reasy\Schedules\Models\ScheduleException;
use App\Modules\Tenant\Reasy\Schedules\Models\ManualBlock;
use App\Modules\Tenant\Reasy\Services\Models\Service;
use App\Modules\Tenant\Reasy\Services\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $plan = Plan::firstOrCreate(['slug' => 'free'], [
        'name' => 'Free Plan',
        'price_monthly' => 0,
        'price_yearly' => 0,
        'amount' => 0,
        'currency' => 'USD',
        'is_active' => true,
        'features' => [],
    ]);

    $this->tenant = Tenant::factory()->create([
        'plan_id' => 'free',
        'status' => 'active',
    ]);

    tenancy()->initialize($this->tenant);

    $this->location = Location::factory()->create();
    $this->category = ServiceCategory::factory()->create();
    $this->service = Service::factory()->create([
        'category_id' => $this->category->id,
        'duration' => 30,
        'buffer_before' => 0,
        'buffer_after' => 0,
    ]);
    $this->resource = Resource::factory()->create([
        'location_id' => $this->location->id,
    ]);
});

it('generates slots based on base schedule', function () {
    // Monday schedule: 09:00 - 10:00 (for simplicity)
    Schedule::factory()->create([
        'resource_id' => $this->resource->id,
        'day_of_week' => 1, // Monday
        'start_time' => '09:00:00',
        'end_time' => '10:00:00',
    ]);

    $date = '2026-06-15'; // A Monday
    $data = new GenerateSlotsData(
        resource_id: $this->resource->id,
        service_id: $this->service->id,
        date: $date
    );

    $action = app(GenerateSlotsAction::class);
    $slots = $action->execute($data);

    // Expected slots (15 min granularity): 09:00, 09:15, 09:30
    // 09:45 is not available because service takes 30 min (ends at 10:15 > 10:00)
    expect($slots)->toHaveCount(3);
    expect($slots[0]['start_time'])->toContain('09:00:00');
    expect($slots[1]['start_time'])->toContain('09:15:00');
    expect($slots[2]['start_time'])->toContain('09:30:00');
});

it('excludes slots overlapping with bookings', function () {
    Schedule::factory()->create([
        'resource_id' => $this->resource->id,
        'day_of_week' => 1,
        'start_time' => '09:00:00',
        'end_time' => '11:00:00',
    ]);

    $customer = Customer::factory()->create();

    // Booking from 09:30 to 10:00
    Booking::create([
        'tenant_id' => $this->tenant->id,
        'location_id' => $this->location->id,
        'service_id' => $this->service->id,
        'resource_id' => $this->resource->id,
        'customer_id' => $customer->id,
        'start_time' => '2026-06-15 09:30:00',
        'end_time' => '2026-06-15 10:00:00',
        'status' => 'confirmed',
        'token' => 'test-token',
        'price' => 1000,
        'currency' => 'USD',
    ]);

    $data = new GenerateSlotsData(
        resource_id: $this->resource->id,
        service_id: $this->service->id,
        date: '2026-06-15'
    );

    $slots = app(GenerateSlotsAction::class)->execute($data);

    // 09:00 (ends 09:30) - OK
    // 09:15 (ends 09:45) - Overlaps with 09:30-10:00 - NO
    // 09:30 (ends 10:00) - Overlaps - NO
    // 09:45 (ends 10:15) - Overlaps - NO
    // 10:00 (ends 10:30) - OK
    // 10:15 (ends 10:45) - OK
    // 10:30 (ends 11:00) - OK
    
    $startTimes = collect($slots)->pluck('start_time')->map(fn($t) => Carbon\Carbon::parse($t)->format('H:i'));
    
    expect($startTimes)->toContain('09:00')
        ->and($startTimes)->not->toContain('09:15')
        ->and($startTimes)->not->toContain('09:30')
        ->and($startTimes)->not->toContain('09:45')
        ->and($startTimes)->toContain('10:00')
        ->and($startTimes)->toContain('10:15')
        ->and($startTimes)->toContain('10:30');
});

it('excludes slots overlapping with manual blocks', function () {
    Schedule::factory()->create([
        'resource_id' => $this->resource->id,
        'day_of_week' => 1,
        'start_time' => '09:00:00',
        'end_time' => '11:00:00',
    ]);

    ManualBlock::factory()->create([
        'resource_id' => $this->resource->id,
        'start_time' => '2026-06-15 10:00:00',
        'end_time' => '2026-06-15 10:30:00',
    ]);

    $data = new GenerateSlotsData(
        resource_id: $this->resource->id,
        service_id: $this->service->id,
        date: '2026-06-15'
    );

    $slots = app(GenerateSlotsAction::class)->execute($data);

    $startTimes = collect($slots)->pluck('start_time')->map(fn($t) => Carbon\Carbon::parse($t)->format('H:i'));
    
    expect($startTimes)->toContain('09:00')
        ->and($startTimes)->not->toContain('09:45')
        ->and($startTimes)->not->toContain('10:00')
        ->and($startTimes)->not->toContain('10:15')
        ->and($startTimes)->toContain('10:30');
});

it('considers service buffers', function () {
    $this->service->update([
        'buffer_before' => 15,
        'buffer_after' => 15,
    ]);

    Schedule::factory()->create([
        'resource_id' => $this->resource->id,
        'day_of_week' => 1,
        'start_time' => '09:00:00',
        'end_time' => '11:00:00',
    ]);

    $data = new GenerateSlotsData(
        resource_id: $this->resource->id,
        service_id: $this->service->id,
        date: '2026-06-15'
    );

    $slots = app(GenerateSlotsAction::class)->execute($data);

    // Slot 09:00: buffer_before starts at 08:45 -> OUT (resource starts at 09:00)
    // Slot 09:15: buffer_before starts at 09:00 -> OK. End at 09:45, buffer_after ends at 10:00 -> OK.
    
    $startTimes = collect($slots)->pluck('start_time')->map(fn($t) => Carbon\Carbon::parse($t)->format('H:i'));
    
    expect($startTimes)->not->toContain('09:00')
        ->and($startTimes)->toContain('09:15');
});
