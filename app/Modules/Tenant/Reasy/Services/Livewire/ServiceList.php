<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Livewire;

use App\Modules\Tenant\Reasy\Services\Models\Service;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ServiceList extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleStatus(string $id): void
    {
        $service = Service::findOrFail($id);
        $service->update(['is_active' => !$service->is_active]);

        $this->dispatch('toast', heading: __('Service Updated'), text: __('Service status changed.'), variant: 'success');
    }

    public function deleteService(string $id): void
    {
        $service = Service::findOrFail($id);
        $service->delete();

        $this->dispatch('toast', heading: __('Service Deleted'), text: __('Service has been removed.'), variant: 'success');
    }

    public function render(): View
    {
        $services = Service::query()
            ->with('category')
            ->when($this->search, fn ($query) => $query->where('name', 'ilike', "%{$this->search}%"))
            ->orderBy('order')
            ->orderBy('name')
            ->paginate(10);

        return view('services-tenant::livewire.service-list', [
            'services' => $services,
        ]);
    }
}
