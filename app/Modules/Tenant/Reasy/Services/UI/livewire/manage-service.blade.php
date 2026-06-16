<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('tenant.reasy.services.index') }}">{{ __('Services') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ $service ? __('Edit Service') : __('New Service') }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="mt-6">
        <flux:heading size="xl">{{ $service ? __('Edit Service') : __('Create Service') }}</flux:heading>
        <flux:subheading>{{ __('Define the details, pricing and availability of your service.') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="mt-8 space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <flux:card class="space-y-6">
                    <flux:heading size="lg">{{ __('General Information') }}</flux:heading>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:input wire:model.live="name" :label="__('Service Name')" :placeholder="__('e.g. Haircut & Style')" required />
                        <flux:input wire:model="slug" :label="__('Slug')" :placeholder="__('e.g. haircut-style')" required />
                    </div>

                    <flux:textarea wire:model="description" :label="__('Description')" :placeholder="__('Describe what this service includes...')" rows="4" />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:select wire:model="category_id" :label="__('Category')" :placeholder="__('Select a category')">
                            @foreach($categories as $category)
                                <flux:select.option :value="$category->id">{{ $category->name }}</flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:select wire:model="cancellation_policy_id" :label="__('Cancellation Policy')" :placeholder="__('Select a policy')">
                            @foreach($policies as $policy)
                                <flux:select.option :value="$policy->id">{{ $policy->name }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                </flux:card>

                <flux:card class="space-y-6">
                    <flux:heading size="lg">{{ __('Duration & Capacity') }}</flux:heading>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <flux:input wire:model="duration" type="number" :label="__('Duration')" suffix="min" required />
                        <flux:input wire:model="buffer_before" type="number" :label="__('Buffer Before')" suffix="min" />
                        <flux:input wire:model="buffer_after" type="number" :label="__('Buffer After')" suffix="min" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:input wire:model="capacity" type="number" :label="__('Capacity')" :description="__('Maximum number of customers per slot.')" required />
                        <flux:input wire:model="order" type="number" :label="__('Display Order')" />
                    </div>
                </flux:card>
            </div>

            <div class="space-y-6">
                <flux:card class="space-y-6">
                    <flux:heading size="lg">{{ __('Pricing & Deposit') }}</flux:heading>

                    <div class="grid grid-cols-1 gap-6">
                        <flux:input wire:model="price" type="number" step="0.01" :label="__('Price')" :suffix="$currency" required />
                        
                        <flux:select wire:model="deposit_type" :label="__('Deposit Type')">
                            <flux:select.option value="none">{{ __('None') }}</flux:select.option>
                            <flux:select.option value="percentage">{{ __('Percentage') }}</flux:select.option>
                            <flux:select.option value="fixed">{{ __('Fixed Amount') }}</flux:select.option>
                        </flux:select>

                        @if($deposit_type !== 'none')
                            <flux:input 
                                wire:model="deposit_value" 
                                type="number" 
                                step="0.01" 
                                :label="$deposit_type === 'percentage' ? __('Deposit Percentage') : __('Deposit Amount')" 
                                :suffix="$deposit_type === 'percentage' ? '%' : $currency" 
                            />
                        @endif
                    </div>
                </flux:card>

                <flux:card class="space-y-6">
                    <flux:heading size="lg">{{ __('Visibility') }}</flux:heading>
                    
                    <div class="space-y-4">
                        <flux:switch wire:model="is_active" :label="__('Active')" :description="__('Allow this service to be booked.')" />
                        <flux:switch wire:model="visible_on_widget" :label="__('Visible on Widget')" :description="__('Show this service in the public booking widget.')" />
                    </div>
                </flux:card>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <flux:button href="{{ route('tenant.reasy.services.index') }}" variant="ghost">{{ __('Cancel') }}</flux:button>
            <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                {{ __('Save Service') }}
            </flux:button>
        </div>
    </form>
</div>
