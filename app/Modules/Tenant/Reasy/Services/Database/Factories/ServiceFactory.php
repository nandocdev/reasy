<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Database\Factories;

use App\Modules\Tenant\Reasy\Services\Models\Service;
use App\Modules\Tenant\Reasy\Services\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $name = fake()->words(3, true);
        return [
            'category_id' => ServiceCategory::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'duration' => fake()->randomElement([30, 45, 60, 90]),
            'buffer_before' => 0,
            'buffer_after' => fake()->randomElement([0, 5, 10, 15]),
            'price' => fake()->numberBetween(1000, 10000), // $10.00 to $100.00
            'currency' => 'USD',
            'deposit_type' => 'none',
            'deposit_value' => 0,
            'capacity' => 1,
            'is_active' => true,
            'visible_on_widget' => true,
            'order' => 0,
        ];
    }
}
