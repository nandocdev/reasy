<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Locations\Livewire;

use App\Modules\Tenant\Reasy\Locations\Actions\SwitchLocationStatusAction;
use App\Modules\Tenant\Reasy\Locations\Models\Location;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class LocationList extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleStatus(string $id, SwitchLocationStatusAction $action): void
    {
        $location = Location::findOrFail($id);
        $action->execute($location);

        $this->dispatch('toast', heading: __('Location Updated'), text: __('Location status changed.'), variant: 'success');
    }

    public function deleteLocation(string $id): void
    {
        $location = Location::findOrFail($id);
        $location->delete();

        $this->dispatch('toast', heading: __('Location Deleted'), text: __('Location has been removed.'), variant: 'success');
    }

    public function render(): View
    {
        $locations = Location::query()
            ->when($this->search, fn ($query) => $query->where('name', 'ilike', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(10);

        return view('locations-tenant::livewire.location-list', [
            'locations' => $locations,
        ]);
    }
}
