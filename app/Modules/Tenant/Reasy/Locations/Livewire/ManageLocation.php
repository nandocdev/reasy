<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Locations\Livewire;

use App\Modules\Tenant\Reasy\Locations\Actions\CreateLocationAction;
use App\Modules\Tenant\Reasy\Locations\Actions\UpdateLocationAction;
use App\Modules\Tenant\Reasy\Locations\DTOs\LocationData;
use App\Modules\Tenant\Reasy\Locations\Models\Location;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ManageLocation extends Component
{
    public ?Location $location = null;
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public string $address = '';
    public string $city = '';
    public string $timezone = 'America/Panama';
    public bool $is_active = true;

    public function mount(?string $id = null): void
    {
        if ($id) {
            $this->location = Location::findOrFail($id);
            $this->name = $this->location->name;
            $this->slug = $this->location->slug;
            $this->description = $this->location->description ?? '';
            $this->address = $this->location->address ?? '';
            $this->city = $this->location->city ?? '';
            $this->timezone = $this->location->timezone;
            $this->is_active = $this->location->is_active;
        }
    }

    public function updatedName($value): void
    {
        if (! $this->location) {
            $this->slug = Str::slug($value);
        }
    }

    public function save(CreateLocationAction $createAction, UpdateLocationAction $updateAction)
    {
        $data = LocationData::from([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'address' => $this->address,
            'city' => $this->city,
            'timezone' => $this->timezone,
            'is_active' => $this->is_active,
        ]);

        if ($this->location) {
            $updateAction->execute($this->location, $data);
            $message = __('Location updated successfully.');
        } else {
            $createAction->execute($data);
            $message = __('Location created successfully.');
        }

        session()->flash('status', $message);
        return redirect()->route('tenant.reasy.locations.index');
    }

    public function render(): View
    {
        return view('locations-tenant::livewire.manage-location');
    }
}
