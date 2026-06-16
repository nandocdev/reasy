<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('tenant.reasy.service-categories.index') }}">{{ __('Categories') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ $category ? __('Edit Category') : __('New Category') }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="mt-6">
        <flux:heading size="xl">{{ $category ? __('Edit Category') : __('Create Category') }}</flux:heading>
        <flux:subheading>{{ __('Organize your services by adding or editing a category.') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="mt-8 space-y-6">
        <flux:card class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:input wire:model.live="name" :label="__('Category Name')" :placeholder="__('e.g. Spa Treatments')" required />
                <flux:input wire:model="slug" :label="__('Slug')" :placeholder="__('e.g. spa-treatments')" required />
            </div>

            <flux:textarea wire:model="description" :label="__('Description')" :placeholder="__('Short description of the category...')" rows="3" />

            <flux:input wire:model="order" type="number" :label="__('Display Order')" :placeholder="__('0')" required />
        </flux:card>

        <div class="flex justify-end gap-3">
            <flux:button href="{{ route('tenant.reasy.service-categories.index') }}" variant="ghost">{{ __('Cancel') }}</flux:button>
            <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                {{ __('Save Category') }}
            </flux:button>
        </div>
    </form>
</div>
