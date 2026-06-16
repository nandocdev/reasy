<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Livewire;

use App\Modules\Tenant\Reasy\Services\Models\CancellationPolicy;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class CancellationPolicyList extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deletePolicy(string $id): void
    {
        $policy = CancellationPolicy::findOrFail($id);
        
        if ($policy->is_default) {
            $this->dispatch('toast', heading: __('Error'), text: __('Cannot delete the default policy.'), variant: 'danger');
            return;
        }

        $policy->delete();

        $this->dispatch('toast', heading: __('Policy Deleted'), text: __('Cancellation policy has been removed.'), variant: 'success');
    }

    public function render(): View
    {
        $policies = CancellationPolicy::query()
            ->when($this->search, fn ($query) => $query->where('name', 'ilike', "%{$this->search}%"))
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->paginate(10);

        return view('services-tenant::livewire.cancellation-policy-list', [
            'policies' => $policies,
        ]);
    }
}
