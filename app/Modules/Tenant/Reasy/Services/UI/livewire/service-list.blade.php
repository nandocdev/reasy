<div class="flex flex-col gap-6 py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Services') }}</flux:heading>
            <flux:subheading>{{ __('Manage the services you offer to your customers.') }}</flux:subheading>
        </div>
        <flux:button href="{{ route('tenant.reasy.services.create') }}" variant="primary" icon="plus">
            {{ __('Add Service') }}
        </flux:button>
    </div>

    <flux:card class="space-y-4">
        <div class="flex items-center gap-4">
            <flux:input wire:model.live="search" :placeholder="__('Search services...')" icon="magnifying-glass" class="max-w-xs" />
        </div>

        <flux:table :paginate="$services">
            <flux:table.columns>
                <flux:table.column sortable>{{ __('Name') }}</flux:table.column>
                <flux:table.column>{{ __('Category') }}</flux:table.column>
                <flux:table.column>{{ __('Price') }}</flux:table.column>
                <flux:table.column>{{ __('Duration') }}</flux:table.column>
                <flux:table.column>{{ __('Status') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($services as $service)
                    <flux:table.row :key="$service->id">
                        <flux:table.cell class="font-medium">
                            <div class="flex flex-col">
                                <span>{{ $service->name }}</span>
                                <span class="text-xs text-zinc-500">{{ $service->slug }}</span>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $service->category?->name ?: '-' }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ number_format($service->price / 100, 2) }} {{ $service->currency }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $service->duration }} min
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:switch wire:click="toggleStatus('{{ $service->id }}')" :checked="$service->is_active" />
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                                <flux:menu>
                                    <flux:menu.item href="{{ route('tenant.reasy.services.edit', $service) }}" icon="pencil">{{ __('Edit') }}</flux:menu.item>
                                    <flux:menu.separator />
                                    <flux:menu.item wire:click="deleteService('{{ $service->id }}')" variant="danger" icon="trash">{{ __('Delete') }}</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
