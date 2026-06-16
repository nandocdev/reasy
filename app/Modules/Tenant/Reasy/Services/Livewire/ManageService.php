<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Livewire;

use App\Modules\Tenant\Reasy\Services\Actions\CreateServiceAction;
use App\Modules\Tenant\Reasy\Services\Actions\UpdateServiceAction;
use App\Modules\Tenant\Reasy\Services\DTOs\ServiceData;
use App\Modules\Tenant\Reasy\Services\Models\CancellationPolicy;
use App\Modules\Tenant\Reasy\Services\Models\Service;
use App\Modules\Tenant\Reasy\Services\Models\ServiceCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ManageService extends Component
{
    public ?Service $service = null;
    
    public string $name = '';
    public string $slug = '';
    public ?string $category_id = null;
    public ?string $cancellation_policy_id = null;
    public string $description = '';
    public int $duration = 30;
    public int $buffer_before = 0;
    public int $buffer_after = 0;
    public float $price = 0.00;
    public string $currency = 'USD';
    public string $deposit_type = 'none';
    public float $deposit_value = 0.00;
    public int $capacity = 1;
    public bool $is_active = true;
    public bool $visible_on_widget = true;
    public int $order = 0;

    public function mount(?Service $service = null): void
    {
        if ($service && $service->exists) {
            $this->service = $service;
            $this->name = $service->name;
            $this->slug = $service->slug;
            $this->category_id = $service->category_id;
            $this->cancellation_policy_id = $service->cancellation_policy_id;
            $this->description = $service->description ?? '';
            $this->duration = $service->duration;
            $this->buffer_before = $service->buffer_before;
            $this->buffer_after = $service->buffer_after;
            $this->price = $service->price / 100;
            $this->currency = $service->currency;
            $this->deposit_type = $service->deposit_type;
            $this->deposit_value = $service->deposit_value / 100;
            $this->capacity = $service->capacity;
            $this->is_active = $service->is_active;
            $this->visible_on_widget = $service->visible_on_widget;
            $this->order = $service->order;
        } else {
            $defaultPolicy = CancellationPolicy::where('is_default', true)->first();
            $this->cancellation_policy_id = $defaultPolicy?->id;
        }
    }

    public function updatedName(string $value): void
    {
        if (!$this->service || !$this->service->exists) {
            $this->slug = Str::slug($value);
        }
    }

    public function save(CreateServiceAction $createAction, UpdateServiceAction $updateAction): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug,' . ($this->service?->id ?? 'NULL') . ',id',
            'category_id' => 'nullable|exists:service_categories,id',
            'cancellation_policy_id' => 'nullable|exists:cancellation_policies,id',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'buffer_before' => 'required|integer|min:0',
            'buffer_after' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'deposit_type' => 'required|string|in:none,percentage,fixed',
            'deposit_value' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'visible_on_widget' => 'boolean',
            'order' => 'required|integer|min:0',
        ]);

        $data = new ServiceData(
            name: $this->name,
            duration: (int) $this->duration,
            price: (int) ($this->price * 100),
            slug: $this->slug,
            category_id: $this->category_id,
            cancellation_policy_id: $this->cancellation_policy_id,
            description: $this->description,
            buffer_before: (int) $this->buffer_before,
            buffer_after: (int) $this->buffer_after,
            currency: $this->currency,
            deposit_type: $this->deposit_type,
            deposit_value: (int) ($this->deposit_value * 100),
            capacity: (int) $this->capacity,
            is_active: $this->is_active,
            visible_on_widget: $this->visible_on_widget,
            order: (int) $this->order,
        );

        if ($this->service && $this->service->exists) {
            $updateAction->execute($this->service, $data);
            $message = __('Service updated successfully.');
        } else {
            $createAction->execute($data);
            $message = __('Service created successfully.');
        }

        $this->dispatch('toast', heading: __('Success'), text: $message, variant: 'success');
        $this->redirect(route('tenant.reasy.services.index'), navigate: true);
    }

    public function render(): View
    {
        return view('services-tenant::livewire.manage-service', [
            'categories' => ServiceCategory::orderBy('name')->get(),
            'policies' => CancellationPolicy::orderBy('name')->get(),
        ]);
    }
}
