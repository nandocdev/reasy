<?php

declare(strict_types=1);

namespace App\Modules\Central\Provisioning\Database\Factories;

use App\Modules\Central\Provisioning\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Central\Provisioning\Models\Tenant>
 */
class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'name' => fake()->company(),
            'slug' => fake()->unique()->slug(),
            'email' => fake()->unique()->safeEmail(),
            'plan_id' => 'starter',
            'status' => 'active',
        ];
    }
}
