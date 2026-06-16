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
            <flux:columns>
                <flux:column sortable>{{ __('Name') }}</flux:column>
                <flux:column>{{ __('Category') }}</flux:column>
                <flux:column>{{ __('Price') }}</flux:column>
                <flux:column>{{ __('Duration') }}</flux:column>
                <flux:column>{{ __('Status') }}</flux:column>
                <flux:column align="end">{{ __('Actions') }}</flux:column>
            </flux:columns>

            <flux:rows>
                @foreach ($services as $service)
                    <flux:row :key="$service->id">
                        <flux:cell class="font-medium">
                            <div class="flex flex-col">
                                <span>{{ $service->name }}</span>
                                <span class="text-xs text-zinc-500">{{ $service->slug }}</span>
                            </div>
                        </flux:cell>
                        <flux:cell>
                            {{ $service->category?->name ?: '-' }}
                        </flux:cell>
                        <flux:cell>
                            {{ number_format($service->price / 100, 2) }} {{ $service->currency }}
                        </flux:cell>
                        <flux:cell>
                            {{ $service->duration }} min
                        </flux:cell>
                        <flux:cell>
                            <flux:switch wire:click="toggleStatus('{{ $service->id }}')" :checked="$service->is_active" />
                        </flux:cell>
                        <flux:cell align="end">
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                                <flux:menu>
                                    <flux:menu.item href="{{ route('tenant.reasy.services.edit', $service) }}" icon="pencil">{{ __('Edit') }}</flux:menu.item>
                                    <flux:menu.separator />
                                    <flux:menu.item wire:click="deleteService('{{ $service->id }}')" variant="danger" icon="trash">{{ __('Delete') }}</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:cell>
                    </flux:row>
                @endforeach
            </flux:rows>
        </flux:table>
    </flux:card>
</div>
