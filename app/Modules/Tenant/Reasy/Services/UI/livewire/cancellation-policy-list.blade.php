<div class="flex flex-col gap-6 py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Cancellation Policies') }}</flux:heading>
            <flux:subheading>{{ __('Define how and when customers can cancel their bookings.') }}</flux:subheading>
        </div>
        <flux:button href="{{ route('tenant.reasy.cancellation-policies.create') }}" variant="primary" icon="plus">
            {{ __('Add Policy') }}
        </flux:button>
    </div>

    <flux:card class="space-y-4">
        <div class="flex items-center gap-4">
            <flux:input wire:model.live="search" :placeholder="__('Search policies...')" icon="magnifying-glass" class="max-w-xs" />
        </div>

        <flux:table :paginate="$policies">
            <flux:table.columns>
                <flux:table.column sortable>{{ __('Name') }}</flux:table.column>
                <flux:table.column>{{ __('Status') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($policies as $policy)
                    <flux:table.row :key="$policy->id">
                        <flux:table.cell class="font-medium">
                            {{ $policy->name }}
                            @if ($policy->is_default)
                                <flux:badge color="emerald" size="sm" class="ml-2">{{ __('Default') }}</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                             <flux:badge color="{{ $policy->is_default ? 'emerald' : 'zinc' }}">
                                {{ $policy->is_default ? __('Active') : __('Inactive') }}
                             </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                                <flux:menu>
                                    <flux:menu.item href="{{ route('tenant.reasy.cancellation-policies.edit', $policy) }}" icon="pencil">{{ __('Edit') }}</flux:menu.item>
                                    @if (!$policy->is_default)
                                        <flux:menu.separator />
                                        <flux:menu.item wire:click="deletePolicy('{{ $policy->id }}')" variant="danger" icon="trash">{{ __('Delete') }}</flux:menu.item>
                                    @endif
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
