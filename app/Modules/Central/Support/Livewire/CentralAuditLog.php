<?php

declare(strict_types=1);

namespace App\Modules\Central\Support\Livewire;

use App\Modules\Shared\Models\Activity;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.central.sidebar')]
class CentralAuditLog extends Component
{
    use WithPagination;

    public $search = '';
    public $event = '';

    public function render()
    {
        $activities = Activity::with(['causer', 'subject'])
            ->when($this->search, function ($query) {
                $query->where('description', 'like', '%' . $this->search . '%')
                      ->orWhere('event', 'like', '%' . $this->search . '%');
            })
            ->when($this->event, fn($query) => $query->where('event', $this->event))
            ->latest()
            ->paginate(20);

        return view('support::livewire.central-audit-log', [
            'activities' => $activities,
        ]);
    }
}
