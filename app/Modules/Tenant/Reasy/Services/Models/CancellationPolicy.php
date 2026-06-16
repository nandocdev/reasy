<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Models;

use App\Modules\Shared\Tenancy\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancellationPolicy extends Model
{
    use BelongsToTenant, HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'rules',
        'is_default',
    ];

    protected $casts = [
        'rules' => 'array',
        'is_default' => 'boolean',
    ];

    protected static function newFactory()
    {
        return \App\Modules\Tenant\Reasy\Services\Database\Factories\CancellationPolicyFactory::new();
    }
}
