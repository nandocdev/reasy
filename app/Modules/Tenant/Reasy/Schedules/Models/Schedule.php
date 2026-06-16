<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Schedules\Models;

use App\Modules\Shared\Tenancy\Models\Concerns\BelongsToTenant;
use App\Modules\Tenant\Reasy\Resources\Models\Resource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use BelongsToTenant, HasUuids, HasFactory;

    protected $fillable = [
        'resource_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    protected static function newFactory()
    {
        return \App\Modules\Tenant\Reasy\Schedules\Database\Factories\ScheduleFactory::new();
    }
}
