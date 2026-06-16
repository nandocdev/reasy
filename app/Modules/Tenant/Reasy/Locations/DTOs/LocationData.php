<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Locations\DTOs;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class LocationData extends Data
{
    public function __construct(
        public string $name,
        public string $slug,
        public string|Optional|null $description = null,
        public string|Optional|null $address = null,
        public string|Optional|null $city = null,
        public string|Optional|null $state = null,
        public string|Optional|null $country = null,
        public string|Optional|null $phone = null,
        public string|Optional|null $email = null,
        public string $timezone = 'America/Panama',
        public bool $is_active = true,
        public array|Optional|null $meta = null,
    ) {}

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash'],
            'description' => ['nullable', 'string', 'max:1000'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'timezone' => ['required', 'string', 'max:100'],
            'is_active' => ['boolean'],
            'meta' => ['nullable', 'array'],
        ];
    }
}
