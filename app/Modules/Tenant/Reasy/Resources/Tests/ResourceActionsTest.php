<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Resources\Tests;

use App\Modules\Tenant\Reasy\Locations\Models\Location;
use App\Modules\Tenant\Reasy\Resources\Actions\RegisterResourceAction;
use App\Modules\Tenant\Reasy\Resources\Actions\UpdateResourceAction;
use App\Modules\Tenant\Reasy\Resources\Actions\AssignServiceToResourceAction;
use App\Modules\Tenant\Reasy\Resources\Actions\SwitchResourceStatusAction;
use App\Modules\Tenant\Reasy\Resources\DTOs\ResourceData;
use App\Modules\Tenant\Reasy\Resources\Models\Resource;
use App\Modules\Tenant\Reasy\Services\Models\Service;
use App\Modules\Central\Provisioning\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    // Setup Tenant context
    $this->tenant = Tenant::factory()->create();
    tenancy()->initialize($this->tenant);
    $this->location = Location::factory()->create();
});

it('can register a resource', function () {
    $data = new ResourceData(
        location_id: $this->location->id,
        name: 'Conference Room A',
        type: 'room',
        capacity: 10
    );

    $action = app(RegisterResourceAction::class);
    $resource = $action->execute($data);

    expect($resource)
        ->toBeInstanceOf(Resource::class)
        ->name->toBe('Conference Room A')
        ->type->toBe('room')
        ->capacity->toBe(10)
        ->tenant_id->toBe($this->tenant->id);
});

it('can update a resource', function () {
    $resource = Resource::factory()->create([
        'location_id' => $this->location->id,
        'name' => 'Old Name',
        'type' => 'equipment'
    ]);

    $data = new ResourceData(
        location_id: $this->location->id,
        name: 'New Name',
        type: 'equipment',
        capacity: 2
    );

    $action = app(UpdateResourceAction::class);
    $updatedResource = $action->execute($resource, $data);

    expect($updatedResource->name)->toBe('New Name');
    expect($updatedResource->capacity)->toBe(2);
});

it('can assign services to a resource', function () {
    $resource = Resource::factory()->create([
        'location_id' => $this->location->id,
    ]);

    $service1 = Service::factory()->create();
    $service2 = Service::factory()->create();

    $action = app(AssignServiceToResourceAction::class);
    $action->execute($resource, [$service1->id, $service2->id]);

    expect($resource->services)->toHaveCount(2)
        ->and($resource->services->pluck('id')->toArray())
        ->toContain($service1->id, $service2->id);
});

it('can switch resource status', function () {
    $resource = Resource::factory()->create([
        'location_id' => $this->location->id,
        'is_active' => true,
    ]);

    $action = app(SwitchResourceStatusAction::class);
    
    $action->execute($resource);
    expect($resource->is_active)->toBeFalse();

    $action->execute($resource);
    expect($resource->is_active)->toBeTrue();
});
