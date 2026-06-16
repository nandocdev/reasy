<?php

use App\Modules\Central\Billing\Models\Plan;
use App\Modules\Central\Provisioning\Models\Tenant;
use App\Modules\Tenant\Reasy\Locations\Models\Location;
use App\Modules\Tenant\Reasy\Resources\Models\Resource;
use App\Modules\Tenant\Reasy\Schedules\Actions\CreateManualBlockAction;
use App\Modules\Tenant\Reasy\Schedules\DTOs\ManualBlockData;
use App\Modules\Tenant\Reasy\Schedules\Models\ManualBlock;
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
    $this->resource = Resource::factory()->create([
        'location_id' => $this->location->id,
    ]);
});

it('can create a manual block', function () {
    $data = new ManualBlockData(
        resource_id: $this->resource->id,
        start_time: '2026-06-15 10:00:00',
        end_time: '2026-06-15 11:00:00',
        reason: 'Meeting',
        meta: ['important' => true]
    );

    $action = app(CreateManualBlockAction::class);
    $block = $action->execute($data);

    expect($block->reason)->toBe('Meeting')
        ->and($block->resource_id)->toBe($this->resource->id)
        ->and($block->tenant_id)->toBe($this->tenant->id)
        ->and($block->meta['important'])->toBeTrue();
    
    $this->assertDatabaseHas('manual_blocks', [
        'id' => $block->id,
        'reason' => 'Meeting'
    ]);
});
