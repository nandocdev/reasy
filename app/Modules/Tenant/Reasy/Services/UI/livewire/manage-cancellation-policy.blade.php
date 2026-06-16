<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('tenant.reasy.cancellation-policies.index') }}">{{ __('Policies') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ $policy ? __('Edit Policy') : __('New Policy') }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="mt-6">
        <flux:heading size="xl">{{ $policy ? __('Edit Cancellation Policy') : __('Create Cancellation Policy') }}</flux:heading>
        <flux:subheading>{{ __('Configure the rules for refunds and cancellations.') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="mt-8 space-y-6">
        <flux:card class="space-y-6">
            <flux:input wire:model="name" :label="__('Policy Name')" :placeholder="__('e.g. Standard Policy')" required />

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <flux:label>{{ __('Cancellation Rules') }}</flux:label>
                    <flux:button wire:click="addRule" size="sm" variant="ghost" icon="plus">
                        {{ __('Add Rule') }}
                    </flux:button>
                </div>

                <div class="space-y-3">
                    @foreach($rules as $index => $rule)
                        <div class="flex items-end gap-3 p-3 rounded-lg bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
                            <flux:input 
                                wire:model="rules.{{ $index }}.before_hours" 
                                type="number" 
                                :label="__('Hours Before')" 
                                class="flex-1"
                                suffix="hrs"
                            />
                            <flux:input 
                                wire:model="rules.{{ $index }}.refund_percent" 
                                type="number" 
                                :label="__('Refund %')" 
                                class="flex-1"
                                suffix="%"
                            />
                            <flux:button 
                                wire:click="removeRule({{ $index }})" 
                                variant="ghost" 
                                color="red" 
                                icon="trash" 
                                size="sm" 
                                class="mb-1"
                            />
                        </div>
                    @endforeach
                    @error('rules') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
            </div>

            <flux:checkbox wire:model="is_default" :label="__('Set as default policy')" :description="__('This policy will be applied to all new services by default.')" />
        </flux:card>

        <div class="flex justify-end gap-3">
            <flux:button href="{{ route('tenant.reasy.cancellation-policies.index') }}" variant="ghost">{{ __('Cancel') }}</flux:button>
            <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                {{ __('Save Policy') }}
            </flux:button>
        </div>
    </form>
</div>
