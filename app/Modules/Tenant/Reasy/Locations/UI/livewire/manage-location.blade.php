<div class="flex flex-col gap-6 py-12 px-4 sm:px-6 lg:px-8 max-w-2xl mx-auto">
    <div>
        <flux:heading size="xl">{{ $location ? __('Edit Location') : __('New Location') }}</flux:heading>
        <flux:subheading>{{ __('Provide details for this branch.') }}</flux:subheading>
    </div>

    <flux:card>
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:input wire:model.blur="name" :label="__('Location Name')" placeholder="Main Branch" required />
                <flux:input wire:model="slug" :label="__('Slug')" placeholder="main-branch" required :disabled="$location" />
            </div>

            <flux:textarea wire:model="description" :label="__('Description')" placeholder="Briefly describe this branch..." />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:input wire:model="address" :label="__('Address')" />
                <flux:input wire:model="city" :label="__('City')" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                <flux:select wire:model="timezone" :label="__('Timezone')" required>
                    <option value="America/Panama">America/Panama</option>
                    <option value="America/Bogota">America/Bogota</option>
                    <option value="America/Mexico_City">America/Mexico_City</option>
                    <option value="UTC">UTC</option>
                </flux:select>

                <div class="flex items-center gap-2 pb-2">
                    <flux:switch wire:model="is_active" />
                    <flux:label>{{ __('Active') }}</flux:label>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <flux:button href="{{ route('tenant.reasy.locations.index') }}" variant="ghost">{{ __('Cancel') }}</flux:button>
                <flux:button type="submit" variant="primary">{{ __('Save Location') }}</flux:button>
            </div>
        </form>
    </flux:card>
</div>
