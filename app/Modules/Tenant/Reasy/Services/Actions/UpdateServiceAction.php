<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Actions;

use App\Modules\Tenant\Reasy\Services\DTOs\ServiceData;
use App\Modules\Tenant\Reasy\Services\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UpdateServiceAction
{
    public function execute(Service $service, ServiceData $data): Service
    {
        $slug = $data->slug ?: Str::slug($data->name);

        if (Service::where('slug', $slug)->where('id', '!=', $service->id)->exists()) {
            throw ValidationException::withMessages([
                'slug' => __('The service slug must be unique within the tenant.'),
            ]);
        }

        $service->update([
            'category_id' => $data->category_id,
            'cancellation_policy_id' => $data->cancellation_policy_id,
            'name' => $data->name,
            'slug' => $slug,
            'description' => $data->description,
            'duration' => $data->duration,
            'buffer_before' => $data->buffer_before,
            'buffer_after' => $data->buffer_after,
            'price' => $data->price,
            'currency' => $data->currency,
            'deposit_type' => $data->deposit_type,
            'deposit_value' => $data->deposit_value,
            'capacity' => $data->capacity,
            'is_active' => $data->is_active,
            'visible_on_widget' => $data->visible_on_widget,
            'order' => $data->order,
        ]);

        return $service;
    }
}
