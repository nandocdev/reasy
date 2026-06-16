<?php

namespace App\Modules\Tenant\Reasy\Resources\Database\Factories;

use App\Modules\Tenant\Reasy\Resources\Models\Resource;
use App\Modules\Tenant\Reasy\Locations\Models\Location;
use App\Modules\Tenant\Identity\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    public function definition(): array
    {
        return [
            'location_id' => Location::factory(),
            'user_id' => null,
            'name' => $this->faker->words(3, true),
            'type' => $this->faker->randomElement(['staff', 'room', 'equipment']),
            'description' => $this->faker->sentence(),
            'avatar_path' => null,
            'capacity' => $this->faker->numberBetween(1, 10),
            'is_active' => true,
            'meta' => null,
        ];
    }
}
