<flux:main>
    <flux:container>
        <div class="flex flex-col gap-6 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="xl" level="1">{{ __('Resource Availability') }}</flux:heading>
                    <flux:subheading>{{ __('Manage base schedules, exceptions, and manual blocks for your resources.') }}</flux:subheading>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="{{ __('Search resources...') }}" class="max-w-sm" />
                </div>

                <flux:card class="overflow-hidden">
                    <flux:table :paginate="$resources">
                        <flux:table.columns>
                            <flux:table.column>{{ __('Resource') }}</flux:table.column>
                            <flux:table.column>{{ __('Type') }}</flux:table.column>
                            <flux:table.column>{{ __('Location') }}</flux:table.column>
                            <flux:table.column>{{ __('Status') }}</flux:table.column>
                            <flux:table.column align="end"></flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @foreach ($resources as $resource)
                                <flux:table.row :key="$resource->id">
                                    <flux:table.cell class="flex items-center gap-3">
                                        <flux:avatar :name="$resource->name" size="sm" />
                                        <div class="flex flex-col">
                                            <span class="font-medium text-sm text-zinc-900 dark:text-white">{{ $resource->name }}</span>
                                            <span class="text-xs text-zinc-500">{{ ucfirst($resource->type) }}</span>
                                        </div>
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <flux:badge size="sm" variant="outline" color="zinc" inset="top bottom">{{ ucfirst($resource->type) }}</flux:badge>
                                    </flux:table.cell>
                                    <flux:table.cell class="text-sm text-zinc-500">
                                        {{ $resource->location->name }}
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        @if($resource->is_active)
                                            <flux:badge size="sm" variant="success">{{ __('ACTIVE') }}</flux:badge>
                                        @else
                                            <flux:badge size="sm" variant="danger">{{ __('INACTIVE') }}</flux:badge>
                                        @endif
                                    </flux:table.cell>
                                    <flux:table.cell align="end">
                                        <flux:button variant="ghost" size="sm" icon="calendar-days" wire:click="selectResource('{{ $resource->id }}')" />
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                </flux:card>
            </div>

            @if($selectedResource)
                <flux:modal name="resource-schedule" class="min-w-[45rem]">
                    <div class="space-y-6" x-data="{ tab: 'base' }">
                        <div>
                            <flux:heading size="lg">{{ __('Availability for') }} {{ $selectedResource->name }}</flux:heading>
                            <flux:subheading>{{ $selectedResource->location->name }} ({{ $selectedResource->location->timezone }})</flux:subheading>
                        </div>

                        {{-- Navegación de Pestañas (Custom Alpine para evitar dependencia de Pro) --}}
                        <div class="flex items-center gap-2 p-1 bg-zinc-100 dark:bg-zinc-800 rounded-lg">
                            <button 
                                @click="tab = 'base'" 
                                :class="tab === 'base' ? 'bg-white dark:bg-zinc-700 shadow-sm text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
                                class="flex-1 flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium rounded-md transition-all"
                            >
                                <flux:icon icon="clock" variant="micro" />
                                {{ __('Base Schedule') }}
                            </button>
                            <button 
                                @click="tab = 'blocks'" 
                                :class="tab === 'blocks' ? 'bg-white dark:bg-zinc-700 shadow-sm text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
                                class="flex-1 flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium rounded-md transition-all"
                            >
                                <flux:icon icon="no-symbol" variant="micro" />
                                {{ __('Manual Blocks') }}
                            </button>
                            <button 
                                @click="tab = 'exceptions'" 
                                :class="tab === 'exceptions' ? 'bg-white dark:bg-zinc-700 shadow-sm text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
                                class="flex-1 flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium rounded-md transition-all"
                            >
                                <flux:icon icon="exclamation-circle" variant="micro" />
                                {{ __('Exceptions') }}
                            </button>
                        </div>

                        {{-- Contenido de Pestañas --}}
                        <div class="min-h-[250px]">
                            <div x-show="tab === 'base'" x-cloak>
                                <div class="py-4 space-y-4">
                                    <flux:text>{{ __('Base schedule configuration logic goes here.') }}</flux:text>
                                    {{-- Aquí iría la cuadrícula de días de la semana --}}
                                </div>
                            </div>

                            <div x-show="tab === 'blocks'" x-cloak>
                                <div class="py-4 space-y-4">
                                    <div class="flex justify-between items-center">
                                        <flux:text>{{ __('Upcoming Manual Blocks') }}</flux:text>
                                        <flux:button size="sm" icon="plus" variant="outline">{{ __('Add Block') }}</flux:button>
                                    </div>
                                    <div class="text-zinc-500 text-sm italic">{{ __('No manual blocks scheduled.') }}</div>
                                </div>
                            </div>

                            <div x-show="tab === 'exceptions'" x-cloak>
                                <div class="py-4 space-y-4">
                                    <div class="flex justify-between items-center">
                                        <flux:text>{{ __('Holiday & Schedule Exceptions') }}</flux:text>
                                        <flux:button size="sm" icon="plus" variant="outline">{{ __('Add Exception') }}</flux:button>
                                    </div>
                                    <div class="text-zinc-500 text-sm italic">{{ __('No exceptions defined.') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <flux:spacer />
                            <flux:modal.close>
                                <flux:button variant="ghost">{{ __('Close') }}</flux:button>
                            </flux:modal.close>
                        </div>
                    </div>
                </flux:modal>
            @endif

            @script
            <script>
                $wire.on('resource-selected', () => {
                    $flux.modal('resource-schedule').show();
                });
            </script>
            @endscript
        </div>
    </flux:container>
</flux:main>
