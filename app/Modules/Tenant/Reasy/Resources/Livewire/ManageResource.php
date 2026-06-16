<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Resources\Livewire;

use App\Modules\Tenant\Reasy\Resources\Actions\RegisterResourceAction;
use App\Modules\Tenant\Reasy\Resources\Actions\UpdateResourceAction;
use App\Modules\Tenant\Reasy\Resources\Actions\AssignServiceToResourceAction;
use App\Modules\Tenant\Reasy\Resources\DTOs\ResourceData;
use App\Modules\Tenant\Reasy\Resources\Models\Resource;
use App\Modules\Tenant\Reasy\Locations\Models\Location;
use App\Modules\Tenant\Reasy\Services\Models\Service;
use App\Modules\Tenant\Identity\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ManageResource extends Component
{
    public ?Resource $resource = null;
    public string $name = '';
    public string $type = 'staff';
    public string $location_id = '';
    public ?string $user_id = null;
    public string $description = '';
    public int $capacity = 1;
    public bool $is_active = true;

    // To hold selected services
    public array $selected_services = [];

    public function mount(?string $id = null): void
    {
        if ($id) {
            $this->resource = Resource::with('services')->findOrFail($id);
            $this->name = $this->resource->name;
            $this->type = $this->resource->type;
            $this->location_id = $this->resource->location_id;
            $this->user_id = $this->resource->user_id;
            $this->description = $this->resource->description ?? '';
            $this->capacity = $this->resource->capacity;
            $this->is_active = $this->resource->is_active;

            $this->selected_services = $this->resource->services->pluck('id')->toArray();
        } else {
            // Default location if available
            $this->location_id = Location::first()?->id ?? '';
        }
    }

    public function updatedType(): void
    {
        if ($this->type !== 'staff') {
            $this->user_id = null;
        }
    }

    public function save(
        RegisterResourceAction $createAction, 
        UpdateResourceAction $updateAction,
        AssignServiceToResourceAction $assignAction
    ) {
        $this->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:staff,room,equipment',
            'location_id' => 'required|exists:reasy_locations,id',
            'capacity' => 'required|integer|min:1',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $data = ResourceData::from([
            'name' => $this->name,
            'type' => $this->type,
            'location_id' => $this->location_id,
            'user_id' => ($this->type === 'staff' && !empty($this->user_id)) ? $this->user_id : null,
            'description' => $this->description,
            'capacity' => $this->capacity,
            'is_active' => $this->is_active,
        ]);

        if ($this->resource) {
            $resource = $updateAction->execute($this->resource, $data);
            $message = __('Resource updated successfully.');
        } else {
            $resource = $createAction->execute($data);
            $message = __('Resource created successfully.');
        }

        // Sync services
        $assignAction->execute($resource, $this->selected_services);

        session()->flash('status', $message);
        return redirect()->route('tenant.reasy.resources.index');
    }

    public function render(): View
    {
        return view('resources-tenant::livewire.manage-resource', [
            'locations' => Location::all(),
            'users' => User::all(),
            'services' => Service::all(),
        ]);
    }
}
