<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Customers\Models;

use App\Modules\Shared\Tenancy\Models\Concerns\BelongsToTenant;
use App\Modules\Tenant\Reasy\Bookings\Models\Booking;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use BelongsToTenant, HasUuids, SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'birth_date',
        'status',
        'meta',
        'internal_notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'meta' => 'array',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    protected static function newFactory()
    {
        return \App\Modules\Tenant\Reasy\Customers\Database\Factories\CustomerFactory::new();
    }
}
