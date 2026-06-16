<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Settings\Models;

use App\Modules\Shared\Tenancy\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TenantSetting extends Model
{
    use BelongsToTenant, HasUuids;

    protected $table = 'tenant_settings';

    protected $fillable = [
        'id',
        'tenant_id',
        'name',
        'logo_path',
        'primary_color',
        'timezone',
        'locale',
        'currency',
        'mfa_required',
        'smtp_host',
        'smtp_port',
        'smtp_user',
        'smtp_password',
        'smtp_from_email',
        'smtp_from_name',
        'smtp_verified',
        'paguelo_facil_api_key',
        'paguelo_facil_webhook_secret',
    ];

    protected $casts = [
        'mfa_required' => 'boolean',
        'smtp_verified' => 'boolean',
        'smtp_password' => 'encrypted',
        'paguelo_facil_api_key' => 'encrypted',
        'paguelo_facil_webhook_secret' => 'encrypted',
    ];
}
