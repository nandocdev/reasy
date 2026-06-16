<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Schedules\Models;

use App\Modules\Shared\Tenancy\Models\Concerns\BelongsToTenant;
use App\Modules\Tenant\Reasy\Resources\Models\Resource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ScheduleException extends Model
{
    use BelongsToTenant, HasUuids;

    protected $fillable = [
        'resource_id',
        'date',
        'start_time',
        'end_time',
        'is_unavailable',
        'reason',
    ];

    protected $casts = [
        'is_unavailable' => 'boolean',
        'date' => 'date',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
