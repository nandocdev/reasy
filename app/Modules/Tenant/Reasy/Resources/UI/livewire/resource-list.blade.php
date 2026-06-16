<div class="flex flex-col gap-6 py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Resources & Staff') }}</flux:heading>
            <flux:subheading>{{ __('Manage your personnel, rooms, and equipment.') }}</flux:subheading>
        </div>
        <flux:button href="{{ route('tenant.reasy.resources.create') }}" variant="primary" icon="plus">
            {{ __('Add Resource') }}
        </flux:button>
    </div>

    @if (session('status'))
        <flux:text color="emerald">{{ session('status') }}</flux:text>
    @endif

    <flux:card class="space-y-4">
        <div class="flex items-center gap-4">
            <flux:input wire:model.live="search" :placeholder="__('Search resources...')" icon="magnifying-glass" class="max-w-xs" />
            <flux:select wire:model.live="typeFilter" class="max-w-xs" :placeholder="__('All Types')">
                <option value="">{{ __('All Types') }}</option>
                <option value="staff">{{ __('Staff') }}</option>
                <option value="room">{{ __('Room') }}</option>
                <option value="equipment">{{ __('Equipment') }}</option>
            </flux:select>
        </div>

        <flux:table :paginate="$resources">
            <flux:table.columns>
                <flux:table.column sortable>{{ __('Name') }}</flux:table.column>
                <flux:table.column>{{ __('Type') }}</flux:table.column>
                <flux:table.column>{{ __('Location') }}</flux:table.column>
                <flux:table.column>{{ __('Capacity') }}</flux:table.column>
                <flux:table.column>{{ __('Status') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($resources as $resource)
                    <flux:table.row :key="$resource->id">
                        <flux:table.cell class="font-medium">
                            <div class="flex flex-col">
                                <span>{{ $resource->name }}</span>
                                @if($resource->user)
                                    <span class="text-xs text-zinc-500">{{ $resource->user->email }}</span>
                                @endif
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            @php
                                $typeColor = match($resource->type) {
                                    'staff' => 'blue',
                                    'room' => 'emerald',
                                    'equipment' => 'amber',
                                    default => 'zinc',
                                };
                            @endphp
                            <flux:badge :color="$typeColor" size="sm">{{ ucfirst($resource->type) }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>{{ $resource->location?->name ?? '-' }}</flux:table.cell>
                        <flux:table.cell>{{ $resource->capacity }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:switch wire:click="toggleStatus('{{ $resource->id }}')" :checked="$resource->is_active" />
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                                <flux:menu>
                                    <flux:menu.item href="{{ route('tenant.reasy.resources.edit', $resource) }}" icon="pencil">{{ __('Edit') }}</flux:menu.item>
                                    <flux:menu.separator />
                                    <flux:menu.item wire:click="deleteResource('{{ $resource->id }}')" variant="danger" icon="trash">{{ __('Delete') }}</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
