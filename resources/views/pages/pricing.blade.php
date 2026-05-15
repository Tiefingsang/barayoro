{{-- resources/views/pages/pricing.blade.php --}}
@extends('layouts.master')

@section('title', 'Tarifs - Barayoro')
@section('description', 'Des formules adaptées à tous les besoins. Commencez gratuitement et évoluez selon vos besoins.')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Tarifs']
    ]" />

    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Des tarifs <span class="text-orange-custom">transparents</span>
                </h1>
                <p class="text-xl text-gray-600">
                    Choisissez la formule qui correspond à vos besoins. Évoluez sans contrainte.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @foreach($plans as $plan)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition {{ $plan->is_popular ? 'ring-2 ring-orange-custom transform scale-105' : '' }}">
                    @if($plan->is_popular)
                    <div class="bg-orange-custom text-white text-center py-2 text-sm font-semibold">
                        Le plus populaire
                    </div>
                    @endif
                    
                    <div class="p-8">
                        <div class="w-14 h-14 {{ $plan->is_popular ? 'gradient-bg' : 'bg-gray-100' }} rounded-xl flex items-center justify-center mb-5">
                            <i class="{{ $plan->icon ?? 'las la-box' }} text-2xl {{ $plan->is_popular ? 'text-white' : 'text-orange-custom' }}"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $plan->name }}</h3>
                        <p class="text-gray-500 mb-6">{{ $plan->subtitle ?? 'Description du plan' }}</p>
                        
                        <div class="mb-6">
                            @if($plan->price == 0)
                                <span class="text-4xl font-bold text-gray-800">Gratuit</span>
                                <span class="text-gray-500">/{{ $plan->period ?? '30 jours' }}</span>
                            @else
                                <span class="text-4xl font-bold text-gray-800">{{ number_format($plan->price, 0, ',', ' ') }} FCFA</span>
                                <span class="text-gray-500">/{{ $plan->period ?? 'mois' }}</span>
                            @endif
                        </div>
                        
                        <a href="{{ $plan->button_url ?? route('register') }}" class="block w-full text-center px-6 py-3 rounded-lg font-semibold transition mb-8 {{ $plan->is_popular ? 'gradient-bg text-white' : 'border-2 border-orange-custom text-orange-custom hover:bg-orange-custom hover:text-white' }}">
                            {{ $plan->button_text ?? 'Commencer' }}
                        </a>
                        
                        <ul class="space-y-3">
                            @if ($plan->features)
                                @foreach($plan->features ?? [] as $feature)
                                <li class="flex items-center gap-3">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span class="text-gray-600">{{ $feature }}</span>
                                </li>
                                @endforeach
                            @endif
                            
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- FAQ Section -->
            <div class="mt-20 text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-8">Questions fréquentes</h2>
                <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto text-left">
                    <div class="bg-white rounded-xl p-6 shadow">
                        <h3 class="font-semibold text-gray-800 mb-2">Puis-je changer de formule ?</h3>
                        <p class="text-gray-600">Oui, vous pouvez changer à tout moment. Le prix est ajusté au prorata.</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow">
                        <h3 class="font-semibold text-gray-800 mb-2">Y a-t-il des frais cachés ?</h3>
                        <p class="text-gray-600">Non, tous nos tarifs sont transparents. Ce que vous voyez est ce que vous payez.</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow">
                        <h3 class="font-semibold text-gray-800 mb-2">Puis-je annuler à tout moment ?</h3>
                        <p class="text-gray-600">Oui, vous pouvez annuler votre abonnement à tout moment, sans frais.</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow">
                        <h3 class="font-semibold text-gray-800 mb-2">Proposez-vous une version gratuite ?</h3>
                        <p class="text-gray-600">Oui, nous avons une version gratuite limitée pour découvrir la plateforme.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection