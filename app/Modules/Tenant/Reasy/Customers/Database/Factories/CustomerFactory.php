<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Customers\Database\Factories;

use App\Modules\Tenant\Reasy\Customers\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'tenant_id' => null,
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'status' => 'active',
        ];
    }
}
