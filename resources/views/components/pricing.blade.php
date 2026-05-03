<section id="tarifs" class="py-20">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-orange-custom font-semibold uppercase tracking-wide">Tarifs</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mt-2 mb-4">
                Des offres adaptées à vos besoins
            </h2>
            <p class="text-gray-600">
                Choisissez le plan qui correspond le mieux à votre entreprise
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @foreach($pricingPlans as $plan)
            <div class="bg-white rounded-2xl p-8 shadow-xl relative overflow-hidden card-hover {{ $plan->is_popular ? 'border-2 border-orange-custom' : '' }}">
                @if($plan->is_popular)
                <div class="absolute top-0 right-0 bg-orange-custom text-white px-4 py-1 rounded-bl-2xl text-sm font-semibold">Populaire</div>
                @endif
                <div class="mb-6">
                    <div class="w-16 h-16 gradient-bg rounded-2xl flex items-center justify-center mb-4">
                        <i class="{{ $plan->icon }} text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $plan->name }}</h3>
                    <p class="text-gray-500 mt-1">{{ $plan->subtitle }}</p>
                </div>
                <div class="mb-6">
                    <span class="text-4xl font-bold">{{ number_format($plan->price, 0, ',', ' ') }}€</span>
                    <span class="text-gray-500">/{{ $plan->period }}</span>
                </div>
                <ul class="space-y-3 mb-8">
                    @foreach($plan->features as $feature)
                    <li class="flex items-center gap-2">
                        <i class="las la-check-circle text-orange-custom"></i> 
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>
                <a href="{{ $plan->button_url }}" class="block w-full text-center py-3 {{ $plan->is_popular ? 'gradient-bg text-white btn-primary' : 'btn-outline' }} rounded-lg transition font-semibold">
                    {{ $plan->button_text }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>