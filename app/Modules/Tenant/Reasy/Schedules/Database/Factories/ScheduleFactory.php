<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Schedules\Database\Factories;

use App\Modules\Tenant\Reasy\Resources\Models\Resource;
use App\Modules\Tenant\Reasy\Schedules\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        return [
            'tenant_id' => null, // Should be set in test
            'resource_id' => Resource::factory(),
            'day_of_week' => $this->faker->numberBetween(0, 6),
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
        ];
    }
}
