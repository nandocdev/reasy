<?php

use App\Modules\Central\Billing\Models\Plan;
use App\Modules\Central\Provisioning\Models\Tenant;
use App\Modules\Tenant\Reasy\Locations\Actions\CreateLocationAction;
use App\Modules\Tenant\Reasy\Locations\Actions\UpdateLocationAction;
use App\Modules\Tenant\Reasy\Locations\Actions\SwitchLocationStatusAction;
use App\Modules\Tenant\Reasy\Locations\DTOs\LocationData;
use App\Modules\Tenant\Reasy\Locations\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
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
});

it('can create a location', function () {
    $data = LocationData::from([
        'name' => 'Main Office',
        'slug' => 'main-office',
        'timezone' => 'America/Panama',
    ]);

    $action = app(CreateLocationAction::class);
    $location = $action->execute($data);

    expect($location->name)->toBe('Main Office')
        ->and($location->slug)->toBe('main-office')
        ->and($location->tenant_id)->toBe($this->tenant->id);
});

it('throws validation exception if slug is duplicated in tenant', function () {
    Location::factory()->create(['slug' => 'duplicate']);
    
    $data = LocationData::from([
        'name' => 'New Location',
        'slug' => 'duplicate',
    ]);

    app(CreateLocationAction::class)->execute($data);
})->throws(ValidationException::class);

it('can update a location', function () {
    $location = Location::factory()->create(['name' => 'Old Name']);
    
    $data = LocationData::from([
        'name' => 'New Name',
        'slug' => 'new-name',
    ]);

    $action = app(UpdateLocationAction::class);
    $updated = $action->execute($location, $data);

    expect($updated->name)->toBe('New Name')
        ->and($updated->slug)->toBe('new-name');
});

it('can switch location status', function () {
    $location = Location::factory()->create(['is_active' => true]);
    $action = app(SwitchLocationStatusAction::class);
    
    $updated = $action->execute($location);
    expect($updated->is_active)->toBeFalse();
    
    $updated = $action->execute($updated);
    expect($updated->is_active)->toBeTrue();
});
