<?php

declare(strict_types=1);

namespace App\Modules\Central\Support\Livewire;

use App\Modules\Central\Provisioning\Models\Tenant;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.central.sidebar')]
class TenantSupportPortal extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $tenants = Tenant::with(['domains', 'plan'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(15);

        return view('support::livewire.tenant-support-portal', [
            'tenants' => $tenants,
        ]);
    }

    public function toggleStatus(string $tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);
        $tenant->status = $tenant->status === 'active' ? 'suspended' : 'active';
        $tenant->suspended_at = $tenant->status === 'suspended' ? now() : null;
        $tenant->save();

        activity('support')
            ->performedOn($tenant)
            ->log("tenant_status_toggled_to_{$tenant->status}");

        $this->dispatch('toast', message: __('Tenant status updated.'));
    }
}
