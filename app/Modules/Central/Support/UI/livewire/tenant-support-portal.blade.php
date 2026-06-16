<flux:main>
    <flux:container>
        <div class="flex flex-col gap-6 py-12 px-6">
            <div>
                <flux:heading size="xl">{{ __('Tenant Support Portal') }}</flux:heading>
                <flux:subheading>{{ __('Search and manage tenant accounts for troubleshooting and assistance.') }}</flux:subheading>
            </div>

            <div class="space-y-6">
                <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="{{ __('Search by name, slug or owner email...') }}" class="max-w-md" />

                <flux:card class="overflow-hidden">
                    <flux:table :paginate="$tenants">
                        <flux:table.columns>
                            <flux:table.column>{{ __('Tenant') }}</flux:table.column>
                            <flux:table.column>{{ __('Plan') }}</flux:table.column>
                            <flux:table.column>{{ __('Status') }}</flux:table.column>
                            <flux:table.column>{{ __('Owner') }}</flux:table.column>
                            <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @foreach ($tenants as $tenant)
                                <flux:table.row :key="$tenant->id">
                                    <flux:table.cell>
                                        <div class="flex flex-col">
                                            <span class="font-medium text-zinc-900 dark:text-white">{{ $tenant->name }}</span>
                                            <span class="text-xs text-zinc-500">{{ $tenant->domains->first()?->domain }}</span>
                                        </div>
                                    </flux:table.cell>

                                    <flux:table.cell>
                                        <flux:badge size="sm" color="zinc" inset="top bottom">
                                            {{ strtoupper($tenant->plan_id) }}
                                        </flux:badge>
                                    </flux:table.cell>

                                    <flux:table.cell>
                                        @if($tenant->status === 'active')
                                            <flux:badge size="sm" variant="success">{{ __('ACTIVE') }}</flux:badge>
                                        @else
                                            <flux:badge size="sm" variant="danger">{{ __('SUSPENDED') }}</flux:badge>
                                        @endif
                                    </flux:table.cell>

                                    <flux:table.cell class="text-sm">
                                        {{ $tenant->email }}
                                    </flux:table.cell>

                                    <flux:table.cell align="end">
                                        <div class="flex items-center justify-end gap-2">
                                            <flux:button icon="user-plus" variant="ghost" size="sm" 
                                                :href="route('tenant.support.auth', ['tenant' => $tenant->id])" 
                                                tooltip="{{ __('Impersonate') }}" />
                                            
                                            <flux:button :icon="$tenant->status === 'active' ? 'pause' : 'play'" 
                                                variant="ghost" size="sm" 
                                                wire:click="toggleStatus('{{ $tenant->id }}')"
                                                wire:confirm="{{ __('Are you sure you want to change this tenant status?') }}" />
                                        </div>
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                </flux:card>
            </div>
        </div>
    </flux:container>
</flux:main>
