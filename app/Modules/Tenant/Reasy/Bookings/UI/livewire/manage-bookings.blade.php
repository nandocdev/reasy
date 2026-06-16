<flux:main>
    <flux:container>
        <div class="flex flex-col gap-6 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="xl" level="1">{{ __('Bookings') }}</flux:heading>
                    <flux:subheading>{{ __('Manage and track all appointments.') }}</flux:subheading>
                </div>
            </div>

            <flux:card class="overflow-hidden">
                <flux:table :paginate="$bookings">
                    <flux:table.columns>
                        <flux:table.column>{{ __('Customer') }}</flux:table.column>
                        <flux:table.column>{{ __('Service') }}</flux:table.column>
                        <flux:table.column>{{ __('Resource') }}</flux:table.column>
                        <flux:table.column>{{ __('Time') }}</flux:table.column>
                        <flux:table.column>{{ __('Status') }}</flux:table.column>
                        <flux:table.column align="end"></flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach ($bookings as $booking)
                            <flux:table.row :key="$booking->id">
                                <flux:table.cell>
                                    <div class="font-medium text-sm">{{ $booking->customer->name }}</div>
                                    <div class="text-xs text-zinc-500">{{ $booking->customer->email }}</div>
                                </flux:table.cell>
                                <flux:table.cell>{{ $booking->service->name }}</flux:table.cell>
                                <flux:table.cell>{{ $booking->resource->name }}</flux:table.cell>
                                <flux:table.cell class="text-sm text-zinc-500">
                                    {{ $booking->start_time->format('M d, H:i') }}
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge size="sm" variant="outline">{{ ucfirst($booking->status) }}</flux:badge>
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
