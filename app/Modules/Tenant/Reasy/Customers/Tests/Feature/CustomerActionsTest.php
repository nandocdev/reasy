<?php

use App\Modules\Central\Provisioning\Models\Tenant;
use App\Modules\Tenant\Reasy\Customers\Actions\SyncCustomerAction;
use App\Modules\Tenant\Reasy\Customers\Actions\BanCustomerAction;
use App\Modules\Tenant\Reasy\Customers\DTOs\SyncCustomerData;
use App\Modules\Tenant\Reasy\Customers\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->tenant = Tenant::factory()->create();
    tenancy()->initialize($this->tenant);
});

it('syncs a new customer successfully', function () {
    $data = new SyncCustomerData(
        name: 'John Doe',
        email: 'john@example.com'
    );

    $action = app(SyncCustomerAction::class);
    $customer = $action->execute($data);

    expect($customer->name)->toBe('John Doe')
        ->and($customer->tenant_id)->toBe($this->tenant->id);
    
    $this->assertDatabaseHas('customers', [
        'id' => $customer->id,
        'email' => 'john@example.com'
    ]);
});

it('bans a customer successfully', function () {
    $customer = Customer::factory()->create([
        'tenant_id' => $this->tenant->id,
        'status' => 'active'
    ]);

    $action = app(BanCustomerAction::class);
    $customer = $action->execute($customer->id);

    expect($customer->status)->toBe('banned');
    $this->assertDatabaseHas('customers', [
        'id' => $customer->id,
        'status' => 'banned'
    ]);
});
