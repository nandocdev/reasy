<div class="min-h-screen bg-zinc-50 dark:bg-zinc-950">
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 glass border-b border-zinc-200 dark:border-zinc-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" class="h-8 w-auto">
                    @else
                        <span class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white" style="color: {{ $primaryColor }}">{{ $platformName }}</span>
                    @endif
                </div>
                
                <div class="hidden md:flex items-center space-x-8 text-sm font-medium text-zinc-600 dark:text-zinc-400">
                    <a href="#features" class="hover:text-zinc-900 dark:hover:text-white transition-colors">{{ __('Features') }}</a>
                    <a href="#pricing" class="hover:text-zinc-900 dark:hover:text-white transition-colors">{{ __('Pricing') }}</a>
                    <a href="#" class="hover:text-zinc-900 dark:hover:text-white transition-colors">{{ __('Documentation') }}</a>
                </div>

                <div class="flex items-center space-x-4">
                        <flux:button href="/register" variant="primary" size="sm">{{ __('Get Started') }}</flux:button>
                  </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative pt-20 pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <flux:heading size="xl" class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6">
                {{ __('Simplifica tus reservas con') }} <span class="text-gradient">Reasy</span>
            </flux:heading>
            <flux:subheading class="text-xl md:text-2xl text-zinc-600 dark:text-zinc-400 max-w-3xl mx-auto mb-10 leading-relaxed">
                {{ __('La plataforma SaaS de gestión de citas más robusta para negocios de servicios. Reduce no-shows, gestiona múltiples sedes y automatiza tus pagos.') }}
            </flux:subheading>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <flux:button variant="primary" href="/register" class="px-8 py-3 text-lg font-bold shadow-lg shadow-brand-primary/20">{{ __('Empieza Gratis') }}</flux:button>
                <flux:button variant="ghost" href="#features" class="px-8 py-3 text-lg font-bold">{{ __('Saber más') }}</flux:button>
            </div>
        </div>
        
        <!-- Abstract Background Shape -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-0 opacity-20 dark:opacity-10 pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 rounded-full blur-3xl bg-brand-primary"></div>
            <div class="absolute bottom-1/4 right-1/4 w-64 h-64 rounded-full blur-3xl bg-indigo-500"></div>
        </div>
    </header>

    <!-- Features Overview -->
    <section id="features" class="py-24 bg-white dark:bg-zinc-900 border-y border-zinc-200 dark:border-zinc-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <flux:heading size="lg" class="mb-2 text-indigo-600 font-bold uppercase tracking-widest text-sm">{{ __('Optimizado para tu Negocio') }}</flux:heading>
                <flux:heading size="xl" class="font-black">{{ __('Todo lo que necesitas para escalar') }}</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="space-y-4 p-6 rounded-3xl transition-colors hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-xl bg-brand-gradient">
                        <flux:icon icon="no-symbol" variant="solid" />
                    </div>
                    <flux:heading size="lg" class="font-bold">{{ __('Cero No-Shows') }}</flux:heading>
                    <flux:text class="text-zinc-500 leading-relaxed">{{ __('Asegura tus ingresos cobrando depósitos automáticos al momento de reservar. Reembolsos automáticos según tus propias políticas.') }}</flux:text>
                </div>

                <div class="space-y-4 p-6 rounded-3xl transition-colors hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-xl bg-brand-gradient">
                        <flux:icon icon="map-pin" variant="solid" />
                    </div>
                    <flux:heading size="lg" class="font-bold">{{ __('Gestión Multi-Sede') }}</flux:heading>
                    <flux:text class="text-zinc-500 leading-relaxed">{{ __('Controla todas tus sucursales desde un solo panel. Horarios, personal y servicios independientes por ubicación.') }}</flux:text>
                </div>

                <div class="space-y-4 p-6 rounded-3xl transition-colors hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-xl bg-brand-gradient">
                        <flux:icon icon="calendar-days" variant="solid" />
                    </div>
                    <flux:heading size="lg" class="font-bold">{{ __('Disponibilidad Inteligente') }}</flux:heading>
                    <flux:text class="text-zinc-500 leading-relaxed">{{ __('Motor en tiempo real que previene doble-bookings y optimiza los tiempos de tu personal con buffers de preparación.') }}</flux:text>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <flux:heading size="xl" class="mb-4 font-black">{{ __('Precios Simples y Transparentes') }}</flux:heading>
                <flux:subheading>{{ __('Elige el plan que mejor se adapte al tamaño de tu negocio.') }}</flux:subheading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($plans as $plan)
                    <flux:card class="relative flex flex-col p-8 hover-lift {{ $plan->slug === 'professional' ? 'ring-2 ring-brand-primary' : '' }}">
                        @if($plan->slug === 'professional')
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full text-[10px] font-black text-white uppercase tracking-widest shadow-xl bg-brand-gradient">
                                {{ __('Recomendado') }}
                            </div>
                        @endif

                        <div class="mb-8">
                            <flux:heading size="lg" class="text-2xl font-bold mb-1">{{ $plan->name }}</flux:heading>
                            <div class="flex items-baseline gap-1 mt-4">
                                <span class="text-4xl font-black tracking-tight text-zinc-900 dark:text-white">{{ \App\Modules\Shared\Infrastructure\Services\PriceFormatter::format($plan->price_monthly) }}</span>
                                <span class="text-zinc-500 text-sm font-medium">/{{ __('mes') }}</span>
                            </div>
                        </div>

                        <ul class="flex-1 space-y-4 mb-8">
                            @foreach($plan->features['display_features'] ?? [] as $feature)
                                <li class="flex items-center gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <div class="bg-emerald-500/10 p-1 rounded-full">
                                        <flux:icon icon="check" class="text-emerald-500 shrink-0" size="xs" />
                                    </div>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                            
                            @if(isset($plan->features['quotas']))
                                <li class="pt-6 border-t border-zinc-100 dark:border-zinc-800 space-y-3">
                                    <div class="text-[10px] font-black uppercase text-zinc-400 tracking-widest">{{ __('Límites Técnicos') }}</div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-zinc-500">{{ __('Sedes') }}</span>
                                        <span class="font-bold text-zinc-900 dark:text-white">{{ $plan->features['quotas']['locations'] < 0 ? __('Ilimitadas') : $plan->features['quotas']['locations'] }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-zinc-500">{{ __('Personal') }}</span>
                                        <span class="font-bold text-zinc-900 dark:text-white">{{ $plan->features['quotas']['staff'] < 0 ? __('Ilimitado') : $plan->features['quotas']['staff'] }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-zinc-500">{{ __('Reservas/mes') }}</span>
                                        <span class="font-bold text-zinc-900 dark:text-white">{{ $plan->features['quotas']['bookings'] < 0 ? __('Ilimitadas') : number_format($plan->features['quotas']['bookings']) }}</span>
                                    </div>
                                </li>
                            @endif
                        </ul>

                        <flux:button 
                            href="/register?plan={{ $plan->slug }}"
                            variant="{{ $plan->slug === 'professional' ? 'primary' : 'ghost' }}" 
                            class="w-full py-3 font-bold"
                            style="{{ $plan->slug === 'professional' ? 'background-color: ' . $primaryColor : '' }}"
                        >
                            {{ __('Elegir Plan') }}
                        </flux:button>
                    </flux:card>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-zinc-50 dark:bg-zinc-950 border-t border-zinc-200 dark:border-zinc-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex flex-col items-center md:items-start">
                 <div class="flex items-center gap-2 mb-4">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" class="h-6 w-auto">
                    @else
                        <span class="font-bold tracking-tight text-zinc-900 dark:text-white" style="color: {{ $primaryColor }}">{{ $platformName }}</span>
                    @endif
                </div>
                <p class="text-xs text-zinc-500">© 2026 {{ $platformName }}. {{ __('Todos los derechos reservados.') }}</p>
            </div>

            <div class="flex gap-8 text-sm font-medium text-zinc-500">
                <a href="#" class="hover:text-zinc-900 dark:hover:text-white">{{ __('Términos') }}</a>
                <a href="#" class="hover:text-zinc-900 dark:hover:text-white">{{ __('Privacidad') }}</a>
                <a href="#" class="hover:text-zinc-900 dark:hover:text-white">{{ __('Contacto') }}</a>
            </div>
        </div>
    </footer>
</div>
