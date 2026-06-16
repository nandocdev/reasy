<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-12 text-center">
        <flux:heading size="xl" class="mb-2">{{ __('Subscription Checkout') }}</flux:heading>
        <flux:subheading>{{ __('Complete your subscription to :plan', ['plan' => $plan->name]) }}</flux:subheading>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
        <!-- Plan Summary -->
        <div class="lg:col-span-2 space-y-6">
            <flux:card>
                <flux:heading size="lg" class="mb-4">{{ __('Order Summary') }}</flux:heading>
                
                <div class="flex justify-between items-center mb-6">
                    <div class="flex flex-col">
                        <span class="font-bold text-zinc-900 dark:text-white">{{ $plan->name }}</span>
                        <span class="text-xs text-zinc-500 uppercase tracking-wider">{{ __('Monthly Plan') }}</span>
                    </div>
                    <div class="text-xl font-bold text-emerald-600">
                        {{ \App\Modules\Shared\Infrastructure\Services\PriceFormatter::format($plan->amount) }}
                    </div>
                </div>

                <div class="border-t border-zinc-100 dark:border-zinc-800 pt-6">
                    <flux:heading size="sm" class="mb-4 text-zinc-400 uppercase tracking-widest">{{ __('What\'s included') }}</flux:heading>
                    <ul class="space-y-3">
                        @foreach($plan->features as $feature)
                            <li class="flex items-start gap-3 text-sm">
                                <flux:icon icon="check-circle" size="xs" class="text-emerald-500 mt-0.5" />
                                <span class="text-zinc-600 dark:text-zinc-400">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </flux:card>

            <div class="px-4 text-center">
                <flux:text size="sm" color="zinc">
                    {{ __('By completing this transaction, you agree to our Terms of Service and Privacy Policy.') }}
                </flux:text>
            </div>
        </div>

        <!-- Payment Widget -->
        <div class="lg:col-span-3">
            <flux:card class="p-0 overflow-hidden shadow-lg border-zinc-200 dark:border-zinc-700">
                <div class="bg-zinc-50 dark:bg-zinc-800/50 p-6 border-b border-zinc-100 dark:border-zinc-800">
                    <flux:heading size="md">{{ __('Secure Payment') }}</flux:heading>
                    <flux:subheading>{{ __('Pay securely using PagueloFacil / Clave') }}</flux:subheading>
                </div>

                <div class="p-8">
                    <livewire:payments.checkout 
                        :amount="$plan->amount"
                        :description="'Subscription to ' . $plan->name"
                        :display-id="'sub_' . $tenant->id"
                        :email="$tenant->email"
                        :custom-field-values="[
                            'type' => 'subscription',
                            'plan_id' => $plan->id,
                            'tenant_id' => $tenant->id
                        ]"
                    />
                </div>
            </flux:card>

            <div class="mt-8 flex justify-center">
                <flux:button variant="ghost" :href="route('tenant.billing.plans')" wire:navigate icon="arrow-left">
                    {{ __('Choose a different plan') }}
                </flux:button>
            </div>
        </div>
    </div>
</div>
