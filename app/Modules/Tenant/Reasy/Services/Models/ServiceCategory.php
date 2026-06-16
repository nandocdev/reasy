<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Models;

use App\Modules\Shared\Tenancy\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use BelongsToTenant, HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'order',
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'category_id');
    }
}
