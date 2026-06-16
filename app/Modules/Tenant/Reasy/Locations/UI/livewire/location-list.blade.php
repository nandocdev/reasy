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
            <flux:columns>
                <flux:column sortable>{{ __('Name') }}</flux:column>
                <flux:column>{{ __('City') }}</flux:column>
                <flux:column>{{ __('Timezone') }}</flux:column>
                <flux:column>{{ __('Status') }}</flux:column>
                <flux:column align="end">{{ __('Actions') }}</flux:column>
            </flux:columns>

            <flux:rows>
                @foreach ($locations as $location)
                    <flux:row :key="$location->id">
                        <flux:cell class="font-medium">
                            <div class="flex flex-col">
                                <span>{{ $location->name }}</span>
                                <span class="text-xs text-zinc-500">{{ $location->slug }}</span>
                            </div>
                        </flux:cell>
                        <flux:cell>{{ $location->city ?: '-' }}</flux:cell>
                        <flux:cell>{{ $location->timezone }}</flux:cell>
                        <flux:cell>
                            <flux:switch wire:click="toggleStatus('{{ $location->id }}')" :checked="$location->is_active" />
                        </flux:cell>
                        <flux:cell align="end">
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                                <flux:menu>
                                    <flux:menu.item href="{{ route('tenant.reasy.locations.edit', $location) }}" icon="pencil">{{ __('Edit') }}</flux:menu.item>
                                    <flux:menu.separator />
                                    <flux:menu.item wire:click="deleteLocation('{{ $location->id }}')" variant="danger" icon="trash">{{ __('Delete') }}</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:cell>
                    </flux:row>
                @endforeach
            </flux:rows>
        </flux:table>
    </flux:card>
</div>
