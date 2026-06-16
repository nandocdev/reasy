<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Schedules\Database\Factories;

use App\Modules\Tenant\Reasy\Resources\Models\Resource;
use App\Modules\Tenant\Reasy\Schedules\Models\ManualBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

class ManualBlockFactory extends Factory
{
    protected $model = ManualBlock::class;

    public function definition(): array
    {
        return [
            'tenant_id' => null,
            'resource_id' => Resource::factory(),
            'start_time' => now()->addDay()->setTime(10, 0),
            'end_time' => now()->addDay()->setTime(11, 0),
            'reason' => 'Meeting',
        ];
    }
}
