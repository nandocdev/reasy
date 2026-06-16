<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Bookings\Database\Factories;

use App\Modules\Tenant\Reasy\Bookings\Models\Booking;
use App\Modules\Tenant\Reasy\Locations\Models\Location;
use App\Modules\Tenant\Reasy\Resources\Models\Resource;
use App\Modules\Tenant\Reasy\Services\Models\Service;
use App\Modules\Tenant\Reasy\Customers\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'tenant_id' => null,
            'location_id' => Location::factory(),
            'service_id' => Service::factory(),
            'resource_id' => Resource::factory(),
            'customer_id' => Customer::factory(),
            'start_time' => now()->addDay()->setTime(10, 0),
            'end_time' => now()->addDay()->setTime(11, 0),
            'timezone' => 'UTC',
            'status' => 'confirmed',
            'token' => Str::random(32),
            'price' => 1000,
            'deposit_paid' => false,
            'currency' => 'USD',
        ];
    }
}
