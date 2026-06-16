<div class="flex flex-col gap-6 py-12 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
    <div>
        <flux:heading size="xl">{{ $resource ? __('Edit Resource') : __('New Resource') }}</flux:heading>
        <flux:subheading>{{ __('Provide details for this resource (staff, room, or equipment).') }}</flux:subheading>
    </div>

    <flux:card>
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:input wire:model="name" :label="__('Resource Name')" placeholder="John Doe or Room A" required />
                
                <flux:select wire:model.live="type" :label="__('Type')" required>
                    <option value="staff">{{ __('Staff') }}</option>
                    <option value="room">{{ __('Room') }}</option>
                    <option value="equipment">{{ __('Equipment') }}</option>
                </flux:select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:select wire:model="location_id" :label="__('Location')" required>
                    <option value="" disabled>{{ __('Select a location') }}</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </flux:select>

                <flux:input type="number" wire:model="capacity" :label="__('Capacity (People)')" required min="1" />
            </div>

            @if($type === 'staff')
                <div class="grid grid-cols-1 gap-6">
                    <flux:select wire:model="user_id" :label="__('Linked System User (Optional)')">
                        <option value="">{{ __('None') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </flux:select>
                </div>
            @endif

            <flux:textarea wire:model="description" :label="__('Description')" placeholder="Notes about this resource..." />

            <div class="space-y-3">
                <flux:heading size="sm">{{ __('Provided Services') }}</flux:heading>
                <flux:subheading size="sm">{{ __('Select the services this resource can perform or be used for.') }}</flux:subheading>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4">
                    @forelse($services as $service)
                        <flux:checkbox wire:model="selected_services" value="{{ $service->id }}" :label="$service->name" />
                    @empty
                        <span class="text-sm text-zinc-500">{{ __('No services available. Please create services first.') }}</span>
                    @endforelse
                </div>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <flux:switch wire:model="is_active" />
                <flux:label>{{ __('Active & Bookable') }}</flux:label>
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <flux:button href="{{ route('tenant.reasy.resources.index') }}" variant="ghost">{{ __('Cancel') }}</flux:button>
                <flux:button type="submit" variant="primary">{{ __('Save Resource') }}</flux:button>
            </div>
        </form>
    </flux:card>
</div>
