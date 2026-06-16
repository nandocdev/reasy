<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Services\Livewire;

use App\Modules\Tenant\Reasy\Services\Models\ServiceCategory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ServiceCategoryList extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteCategory(string $id): void
    {
        $category = ServiceCategory::findOrFail($id);
        
        if ($category->services()->count() > 0) {
            $this->dispatch('toast', heading: __('Error'), text: __('Cannot delete category with services.'), variant: 'danger');
            return;
        }

        $category->delete();

        $this->dispatch('toast', heading: __('Category Deleted'), text: __('Service category has been removed.'), variant: 'success');
    }

    public function render(): View
    {
        $categories = ServiceCategory::query()
            ->when($this->search, fn ($query) => $query->where('name', 'ilike', "%{$this->search}%"))
            ->orderBy('order')
            ->orderBy('name')
            ->paginate(10);

        return view('services-tenant::livewire.service-category-list', [
            'categories' => $categories,
        ]);
    }
}
