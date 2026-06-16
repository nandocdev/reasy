<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Livewire;

use App\Modules\Tenant\Reasy\Services\Actions\CreateServiceCategoryAction;
use App\Modules\Tenant\Reasy\Services\Actions\UpdateServiceCategoryAction;
use App\Modules\Tenant\Reasy\Services\DTOs\ServiceCategoryData;
use App\Modules\Tenant\Reasy\Services\Models\ServiceCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ManageServiceCategory extends Component
{
    public ?ServiceCategory $category = null;
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public int $order = 0;

    public function mount(?ServiceCategory $category = null): void
    {
        if ($category && $category->exists) {
            $this->category = $category;
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->description = $category->description ?? '';
            $this->order = $category->order;
        }
    }

    public function updatedName(string $value): void
    {
        if (!$this->category || !$this->category->exists) {
            $this->slug = Str::slug($value);
        }
    }

    public function save(CreateServiceCategoryAction $createAction, UpdateServiceCategoryAction $updateAction): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:service_categories,slug,' . ($this->category?->id ?? 'NULL') . ',id',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
        ]);

        $data = new ServiceCategoryData(
            name: $this->name,
            slug: $this->slug,
            description: $this->description,
            order: $this->order,
        );

        if ($this->category && $this->category->exists) {
            $updateAction->execute($this->category, $data);
            $message = __('Category updated successfully.');
        } else {
            $createAction->execute($data);
            $message = __('Category created successfully.');
        }

        $this->dispatch('toast', heading: __('Success'), text: $message, variant: 'success');
        $this->redirect(route('tenant.reasy.service-categories.index'), navigate: true);
    }

    public function render(): View
    {
        return view('services-tenant::livewire.manage-service-category');
    }
}
