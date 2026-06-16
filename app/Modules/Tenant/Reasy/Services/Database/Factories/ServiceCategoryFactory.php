<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Database\Factories;

use App\Modules\Tenant\Reasy\Services\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceCategoryFactory extends Factory
{
    protected $model = ServiceCategory::class;

    public function definition(): array
    {
        $name = fake()->words(2, true);
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
