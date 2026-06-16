<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Models;

use App\Modules\Shared\Tenancy\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use BelongsToTenant, HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'category_id',
        'cancellation_policy_id',
        'name',
        'slug',
        'description',
        'duration',
        'buffer_before',
        'buffer_after',
        'price',
        'currency',
        'deposit_type',
        'deposit_value',
        'capacity',
        'is_active',
        'visible_on_widget',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'visible_on_widget' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function cancellationPolicy()
    {
        return $this->belongsTo(CancellationPolicy::class, 'cancellation_policy_id');
    }

    protected static function newFactory()
    {
        return \App\Modules\Tenant\Reasy\Services\Database\Factories\ServiceFactory::new();
    }
}
