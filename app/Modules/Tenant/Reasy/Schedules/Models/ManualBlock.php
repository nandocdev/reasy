<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Schedules\Models;

use App\Modules\Shared\Tenancy\Models\Concerns\BelongsToTenant;
use App\Modules\Tenant\Reasy\Resources\Models\Resource;
use App\Modules\Tenant\Reasy\Schedules\Database\Factories\ManualBlockFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManualBlock extends Model {
    use BelongsToTenant, HasUuids, HasFactory;

    protected $fillable = [
        'tenant_id',
        'resource_id',
        'start_time',
        'end_time',
        'reason',
        'meta',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'meta' => 'array',
    ];

    /**
     * @return BelongsTo<Resource, self>
     */
    public function resource(): BelongsTo {
        return $this->belongsTo(Resource::class);
    }

    protected static function newFactory() {
        return ManualBlockFactory::new();
    }
}
