<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Actions;

use App\Modules\Tenant\Reasy\Services\DTOs\ServiceCategoryData;
use App\Modules\Tenant\Reasy\Services\Models\ServiceCategory;
use Illuminate\Support\Str;

class CreateServiceCategoryAction
{
    public function execute(ServiceCategoryData $data): ServiceCategory
    {
        $slug = $data->slug ?: Str::slug($data->name);

        return ServiceCategory::create([
            'name' => $data->name,
            'slug' => $slug,
            'description' => $data->description,
            'order' => $data->order,
        ]);
    }
}
