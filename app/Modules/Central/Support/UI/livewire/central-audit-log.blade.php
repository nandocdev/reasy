<flux:main>
    <flux:container>
        <div class="flex flex-col gap-6 py-12">
            <div class="flex items-center justify-between px-6">
                <div>
                    <flux:heading size="xl">{{ __('Central Audit Log') }}</flux:heading>
                    <flux:subheading>{{ __('Monitor all administrative and infrastructure changes across the platform.') }}</flux:subheading>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-center gap-4 px-6">
                    <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="{{ __('Search activity...') }}" class="max-w-sm" />
                    
                    <flux:select wire:model.live="event" class="max-w-xs">
                        <option value="">{{ __('All Events') }}</option>
                        <option value="created">{{ __('Created') }}</option>
                        <option value="updated">{{ __('Updated') }}</option>
                        <option value="deleted">{{ __('Deleted') }}</option>
                    </flux:select>
                </div>

                <flux:card class="overflow-hidden mx-6">
                    <flux:table :paginate="$activities">
                        <flux:table.columns>
                            <flux:table.column>{{ __('Date') }}</flux:table.column>
                            <flux:table.column>{{ __('Actor') }}</flux:table.column>
                            <flux:table.column>{{ __('Action') }}</flux:table.column>
                            <flux:table.column>{{ __('Subject') }}</flux:table.column>
                            <flux:table.column align="end"></flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @foreach ($activities as $activity)
                                <flux:table.row :key="$activity->id">
                                    <flux:table.cell class="text-sm text-zinc-500 whitespace-nowrap">
                                        {{ $activity->created_at->format('M d, H:i:s') }}
                                    </flux:table.cell>
                                    
                                    <flux:table.cell>
                                        @if($activity->causer)
                                            <div class="flex items-center gap-2">
                                                <flux:avatar size="xs" :name="$activity->causer->name" />
                                                <span class="text-sm font-medium">{{ $activity->causer->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-zinc-400 italic">{{ __('System') }}</span>
                                        @endif
                                    </flux:table.cell>

                                    <flux:table.cell>
                                        <flux:badge size="sm" color="zinc" inset="top bottom">
                                            {{ strtoupper($activity->event ?? 'activity') }}
                                        </flux:badge>
                                        <span class="text-sm text-zinc-600 dark:text-zinc-400 ml-2">{{ $activity->description }}</span>
                                    </flux:table.cell>

                                    <flux:table.cell class="text-sm italic text-zinc-500">
                                        @if($activity->subject_type)
                                            {{ class_basename($activity->subject_type) }} ({{ Str::limit($activity->subject_id, 8) }})
                                        @endif
                                    </flux:table.cell>

                                    <flux:table.cell align="end">
                                        <flux:button icon="eye" variant="ghost" size="sm" />
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
