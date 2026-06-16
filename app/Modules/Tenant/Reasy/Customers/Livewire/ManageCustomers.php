<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Customers\Livewire;

use App\Modules\Tenant\Reasy\Customers\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class ManageCustomers extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $customers = Customer::where('tenant_id', tenant('id'))
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%')
                                             ->orWhere('email', 'like', '%' . $this->search . '%'))
            ->latest()
            ->paginate(15);

        return view('customers-tenant::livewire.manage-customers', [
            'customers' => $customers,
        ])->layout('layouts.app.sidebar');
    }
}
