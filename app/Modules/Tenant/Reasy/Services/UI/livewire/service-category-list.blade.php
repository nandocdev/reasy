<div class="flex flex-col gap-6 py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Service Categories') }}</flux:heading>
            <flux:subheading>{{ __('Organize your services into logical groups.') }}</flux:subheading>
        </div>
        <flux:button href="{{ route('tenant.reasy.service-categories.create') }}" variant="primary" icon="plus">
            {{ __('Add Category') }}
        </flux:button>
    </div>

    <flux:card class="space-y-4">
        <div class="flex items-center gap-4">
            <flux:input wire:model.live="search" :placeholder="__('Search categories...')" icon="magnifying-glass" class="max-w-xs" />
        </div>

        <flux:table :paginate="$categories">
            <flux:table.columns>
                <flux:table.column sortable>{{ __('Name') }}</flux:table.column>
                <flux:table.column>{{ __('Slug') }}</flux:table.column>
                <flux:table.column>{{ __('Services Count') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($categories as $category)
                    <flux:table.row :key="$category->id">
                        <flux:table.cell class="font-medium">
                            {{ $category->name }}
                        </flux:table.cell>
                        <flux:table.cell>
                             <flux:text color="zinc">{{ $category->slug }}</flux:text>
                        </flux:table.cell>
                        <flux:table.cell>
                             <flux:badge size="sm" inset="none">{{ $category->services_count ?? $category->services()->count() }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                                <flux:menu>
                                    <flux:menu.item href="{{ route('tenant.reasy.service-categories.edit', $category) }}" icon="pencil">{{ __('Edit') }}</flux:menu.item>
                                    <flux:menu.separator />
                                    <flux:menu.item wire:click="deleteCategory('{{ $category->id }}')" variant="danger" icon="trash">{{ __('Delete') }}</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
