<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Schedules\Database\Factories;

use App\Modules\Tenant\Reasy\Resources\Models\Resource;
use App\Modules\Tenant\Reasy\Schedules\Models\ScheduleException;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleExceptionFactory extends Factory
{
    protected $model = ScheduleException::class;

    public function definition(): array
    {
        return [
            'tenant_id' => null,
            'resource_id' => Resource::factory(),
            'date' => $this->faker->date(),
            'start_time' => null,
            'end_time' => null,
            'is_unavailable' => true,
            'reason' => 'Holiday',
        ];
    }
}
