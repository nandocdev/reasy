<flux:main>
    <flux:container>
        <div class="flex flex-col gap-6 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="xl" level="1">{{ __('Customers') }}</flux:heading>
                    <flux:subheading>{{ __('Manage your customer database and view activity.') }}</flux:subheading>
                </div>
            </div>

            <flux:card class="overflow-hidden">
                <div class="p-4 border-b border-zinc-200 dark:border-zinc-700">
                    <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="{{ __('Search by name or email...') }}" class="max-w-sm" />
                </div>
                <flux:table :paginate="$customers">
                    <flux:table.columns>
                        <flux:table.column>{{ __('Customer') }}</flux:table.column>
                        <flux:table.column>{{ __('Email') }}</flux:table.column>
                        <flux:table.column>{{ __('Status') }}</flux:table.column>
                        <flux:table.column>{{ __('Joined') }}</flux:table.column>
                        <flux:table.column align="end"></flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach ($customers as $customer)
                            <flux:table.row :key="$customer->id">
                                <flux:table.cell class="flex items-center gap-3">
                                    <flux:avatar :name="$customer->name" size="sm" />
                                    <span class="font-medium text-sm text-zinc-900 dark:text-white">{{ $customer->name }}</span>
                                </flux:table.cell>
                                <flux:table.cell class="text-sm text-zinc-500">{{ $customer->email }}</flux:table.cell>
                                <flux:table.cell>
                                    @if($customer->status === 'active')
                                        <flux:badge size="sm" variant="success">{{ __('ACTIVE') }}</flux:badge>
                                    @elseif($customer->status === 'banned')
                                        <flux:badge size="sm" variant="danger">{{ __('BANNED') }}</flux:badge>
                                    @else
                                        <flux:badge size="sm" variant="outline">{{ ucfirst($customer->status) }}</flux:badge>
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell class="text-sm text-zinc-500">
                                    {{ $customer->created_at->format('M d, Y') }}
                                </flux:table.cell>
                                <flux:table.cell align="end">
                                    <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </flux:card>
        </div>
    </flux:container>
</flux:main>
