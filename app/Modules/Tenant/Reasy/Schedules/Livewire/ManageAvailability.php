<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Schedules\Livewire;

use App\Modules\Tenant\Reasy\Resources\Models\Resource;
use Livewire\Component;
use Livewire\WithPagination;

class ManageAvailability extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedResource = null;

    /**
     * [LISTA DE RIESGOS - RUP]
     * 1. [Técnico] Carga excesiva de datos en el modal (Mitigación: Carga diferida de relaciones).
     */
    public function render()
    {
        $resources = Resource::where('tenant_id', tenant('id'))
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->with(['location'])
            ->paginate(10);

        return view('schedules-tenant::livewire.manage-availability', [
            'resources' => $resources,
        ])->layout('layouts.app.sidebar');
    }

    public function selectResource($resourceId)
    {
        $this->selectedResource = Resource::with(['location'])->findOrFail($resourceId);
        
        $this->dispatch('resource-selected');
    }
}
