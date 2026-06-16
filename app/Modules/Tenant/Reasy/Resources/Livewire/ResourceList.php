<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Resources\Livewire;

use App\Modules\Tenant\Reasy\Resources\Actions\SwitchResourceStatusAction;
use App\Modules\Tenant\Reasy\Resources\Models\Resource;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ResourceList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $typeFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function toggleStatus(string $id, SwitchResourceStatusAction $action): void
    {
        $resource = Resource::findOrFail($id);
        $action->execute($resource);

        $this->dispatch('toast', heading: __('Resource Updated'), text: __('Resource status changed.'), variant: 'success');
    }

    public function deleteResource(string $id): void
    {
        $resource = Resource::findOrFail($id);
        $resource->delete();

        $this->dispatch('toast', heading: __('Resource Deleted'), text: __('Resource has been removed.'), variant: 'success');
    }

    public function render(): View
    {
        $resources = Resource::query()
            ->with(['location', 'user'])
            ->when($this->search, fn ($query) => $query->where('name', 'ilike', "%{$this->search}%"))
            ->when($this->typeFilter, fn ($query) => $query->where('type', $this->typeFilter))
            ->orderBy('name')
            ->paginate(10);

        return view('resources-tenant::livewire.resource-list', [
            'resources' => $resources,
        ]);
    }
}
