<div class="flex flex-col gap-6 py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Locations') }}</flux:heading>
            <flux:subheading>{{ __('Manage your business branches and physical spaces.') }}</flux:subheading>
        </div>
        <flux:button href="{{ route('tenant.reasy.locations.create') }}" variant="primary" icon="plus">
            {{ __('Add Location') }}
        </flux:button>
    </div>

    @if (session('status'))
        <flux:text color="emerald">{{ session('status') }}</flux:text>
    @endif

    <flux:card class="space-y-4">
        <div class="flex items-center gap-4">
            <flux:input wire:model.live="search" :placeholder="__('Search locations...')" icon="magnifying-glass" class="max-w-xs" />
        </div>

        <flux:table :paginate="$locations">
            <flux:table.columns>
                <flux:table.column sortable>{{ __('Name') }}</flux:table.column>
                <flux:table.column>{{ __('City') }}</flux:table.column>
                <flux:table.column>{{ __('Timezone') }}</flux:table.column>
                <flux:table.column>{{ __('Status') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($locations as $location)
                    <flux:table.row :key="$location->id">
                        <flux:table.cell class="font-medium">
                            <div class="flex flex-col">
                                <span>{{ $location->name }}</span>
                                <span class="text-xs text-zinc-500">{{ $location->slug }}</span>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>{{ $location->city ?: '-' }}</flux:table.cell>
                        <flux:table.cell>{{ $location->timezone }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:switch wire:click="toggleStatus('{{ $location->id }}')" :checked="$location->is_active" />
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                                <flux:menu>
                                    <flux:menu.item href="{{ route('tenant.reasy.locations.edit', $location) }}" icon="pencil">{{ __('Edit') }}</flux:menu.item>
                                    <flux:menu.separator />
                                    <flux:menu.item wire:click="deleteLocation('{{ $location->id }}')" variant="danger" icon="trash">{{ __('Delete') }}</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
