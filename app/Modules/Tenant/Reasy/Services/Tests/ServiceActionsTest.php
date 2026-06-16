<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Tests;

use App\Modules\Central\Provisioning\Models\Tenant;
use App\Modules\Tenant\Reasy\Services\Actions\CreateCancellationPolicyAction;
use App\Modules\Tenant\Reasy\Services\Actions\CreateServiceAction;
use App\Modules\Tenant\Reasy\Services\Actions\CreateServiceCategoryAction;
use App\Modules\Tenant\Reasy\Services\Actions\UpdateServiceAction;
use App\Modules\Tenant\Reasy\Services\DTOs\CancellationPolicyData;
use App\Modules\Tenant\Reasy\Services\DTOs\ServiceCategoryData;
use App\Modules\Tenant\Reasy\Services\DTOs\ServiceData;
use App\Modules\Tenant\Reasy\Services\Models\CancellationPolicy;
use App\Modules\Tenant\Reasy\Services\Models\Service;
use App\Modules\Tenant\Reasy\Services\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $tenant = Tenant::factory()->create();
    tenancy()->initialize($tenant);
});

test('can create a service category', function () {
    $action = app(CreateServiceCategoryAction::class);
    $data = new ServiceCategoryData(
        name: 'Test Category',
        description: 'Test Description'
    );

    $category = $action->execute($data);

    expect($category)->toBeInstanceOf(ServiceCategory::class)
        ->and($category->name)->toBe('Test Category')
        ->and($category->slug)->toBe('test-category');
});

test('can create a cancellation policy', function () {
    $action = app(CreateCancellationPolicyAction::class);
    $data = new CancellationPolicyData(
        name: 'Standard Policy',
        rules: [['before_hours' => 24, 'refund_percent' => 100]],
        is_default: true
    );

    $policy = $action->execute($data);

    expect($policy)->toBeInstanceOf(CancellationPolicy::class)
        ->and($policy->name)->toBe('Standard Policy')
        ->and($policy->is_default)->toBeTrue();
});

test('can create a service', function () {
    $category = ServiceCategory::create(['name' => 'Cat', 'slug' => 'cat']);
    $policy = CancellationPolicy::create(['name' => 'Pol', 'rules' => []]);

    $action = app(CreateServiceAction::class);
    $data = new ServiceData(
        name: 'Test Service',
        duration: 60,
        price: 5000,
        category_id: $category->id,
        cancellation_policy_id: $policy->id
    );

    $service = $action->execute($data);

    expect($service)->toBeInstanceOf(Service::class)
        ->and($service->name)->toBe('Test Service')
        ->and($service->price)->toBe(5000)
        ->and($service->category_id)->toBe($category->id);
});

test('cannot create service with duplicate slug', function () {
    Service::create([
        'name' => 'Existing',
        'slug' => 'existing',
        'duration' => 30,
        'price' => 1000
    ]);

    $action = app(CreateServiceAction::class);
    $data = new ServiceData(
        name: 'Duplicate',
        slug: 'existing',
        duration: 60,
        price: 5000
    );

    expect(fn() => $action->execute($data))->toThrow(ValidationException::class);
});

test('can update a service', function () {
    $service = Service::create([
        'name' => 'Old Name',
        'slug' => 'old-name',
        'duration' => 30,
        'price' => 1000
    ]);

    $action = app(UpdateServiceAction::class);
    $data = new ServiceData(
        name: 'New Name',
        duration: 45,
        price: 1500
    );

    $updatedService = $action->execute($service, $data);

    expect($updatedService->name)->toBe('New Name')
        ->and($updatedService->duration)->toBe(45)
        ->and($updatedService->price)->toBe(1500);
});

test('can archive a service', function () {
    $service = Service::create([
        'name' => 'To Archive',
        'slug' => 'to-archive',
        'duration' => 30,
        'price' => 1000
    ]);

    $action = app(\App\Modules\Tenant\Reasy\Services\Actions\ArchiveServiceAction::class);
    $action->execute($service);

    expect(Service::find($service->id))->toBeNull();
    expect(Service::withTrashed()->find($service->id))->not->toBeNull();
});
