<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Livewire;

use App\Modules\Tenant\Reasy\Services\Actions\CreateCancellationPolicyAction;
use App\Modules\Tenant\Reasy\Services\Actions\UpdateCancellationPolicyAction;
use App\Modules\Tenant\Reasy\Services\DTOs\CancellationPolicyData;
use App\Modules\Tenant\Reasy\Services\Models\CancellationPolicy;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ManageCancellationPolicy extends Component
{
    public ?CancellationPolicy $policy = null;
    public string $name = '';
    public array $rules = [];
    public bool $is_default = false;

    public function mount(?CancellationPolicy $policy = null): void
    {
        if ($policy && $policy->exists) {
            $this->policy = $policy;
            $this->name = $policy->name;
            $this->rules = $policy->rules;
            $this->is_default = $policy->is_default;
        } else {
            $this->rules = [
                ['before_hours' => 24, 'refund_percent' => 100],
            ];
        }
    }

    public function addRule(): void
    {
        $this->rules[] = ['before_hours' => 0, 'refund_percent' => 0];
    }

    public function removeRule(int $index): void
    {
        unset($this->rules[$index]);
        $this->rules = array_values($this->rules);
    }

    public function save(CreateCancellationPolicyAction $createAction, UpdateCancellationPolicyAction $updateAction): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'rules' => 'required|array|min:1',
            'rules.*.before_hours' => 'required|integer|min:0',
            'rules.*.refund_percent' => 'required|integer|min:0|max:100',
            'is_default' => 'boolean',
        ]);

        $data = new CancellationPolicyData(
            name: $this->name,
            rules: $this->rules,
            is_default: $this->is_default,
        );

        if ($this->policy && $this->policy->exists) {
            $updateAction->execute($this->policy, $data);
            $message = __('Policy updated successfully.');
        } else {
            $createAction->execute($data);
            $message = __('Policy created successfully.');
        }

        $this->dispatch('toast', heading: __('Success'), text: $message, variant: 'success');
        $this->redirect(route('tenant.reasy.cancellation-policies.index'), navigate: true);
    }

    public function render(): View
    {
        return view('services-tenant::livewire.manage-cancellation-policy');
    }
}
