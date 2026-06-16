<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Database\Factories;

use App\Modules\Tenant\Reasy\Services\Models\CancellationPolicy;
use Illuminate\Database\Eloquent\Factories\Factory;

class CancellationPolicyFactory extends Factory
{
    protected $model = CancellationPolicy::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Standard', 'Strict', 'Flexible']),
            'rules' => [
                ['before_hours' => 48, 'refund_percent' => 100],
                ['before_hours' => 24, 'refund_percent' => 50],
            ],
            'is_default' => false,
        ];
    }
}
