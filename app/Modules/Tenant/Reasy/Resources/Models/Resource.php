<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Resources\Models;

use App\Modules\Shared\Tenancy\Models\Concerns\BelongsToTenant;
use App\Modules\Tenant\Reasy\Locations\Models\Location;
use App\Modules\Tenant\Reasy\Services\Models\Service;
use App\Modules\Tenant\Identity\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Tenant\Reasy\Resources\Database\Factories\ResourceFactory;

class Resource extends Model
{
    use BelongsToTenant, HasUuids, SoftDeletes, HasFactory;

    protected $fillable = [
        'location_id',
        'user_id',
        'name',
        'type',
        'description',
        'avatar_path',
        'capacity',
        'is_active',
        'meta',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'meta' => 'array',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'resource_service');
    }

    protected static function newFactory()
    {
        return ResourceFactory::new();
    }
}
