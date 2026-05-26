{{-- resources/views/welcome.blade.php --}}
@extends('layouts.master')

@section('title', 'Barayoro - Solution SaaS de gestion d\'entreprise | Par Masadigitale')
@section('description', 'Barayoro est la solution SaaS tout-en-un pour gérer vos ventes, factures, stocks, projets et équipes. Essai gratuit 30 jours.')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section pt-32 pb-20 md:pt-40 md:pb-28">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 bg-orange-100 text-orange-custom px-4 py-2 rounded-full mb-6">
                        <i class="fas fa-rocket text-sm"></i>
                        <span class="text-sm font-semibold">Solution SaaS N°1 en Afrique</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 mb-6 leading-tight">
                        Gérez votre entreprise 
                        <span class="text-orange-custom">partout, même hors ligne</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        {{ $settings['hero_description'] ?? 'Barayoro est la solution SaaS tout-en-un pour gérer vos ventes, factures, stocks, projets et équipes. Accédez à vos données partout, même sans internet.' }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="px-8 py-4 gradient-bg text-white rounded-xl font-semibold btn-primary inline-flex items-center justify-center gap-2">
                            <i class="fas fa-calendar-alt"></i>
                            Essai gratuit 30 jours
                        </a>
                        <a href="{{ route('features') }}" class="px-8 py-4 bg-white border-2 border-orange-custom text-orange-custom rounded-xl font-semibold hover:bg-orange-custom hover:text-white transition inline-flex items-center justify-center gap-2">
                            <i class="fas fa-play"></i>
                            Voir la démo
                        </a>
                    </div>
                    
                    <!-- Trusted by - Version dynamique -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-500 mb-3">
                            Déjà plus de {{ number_format($totalCompaniesCount ?? 0) }} entreprises nous font confiance
                        </p>
                        <div class="flex flex-wrap gap-6 justify-center lg:justify-start">
                            @forelse($trustedCompanies ?? [] as $company)
                                @if($company->logo && Storage::disk('public')->exists($company->logo))
                                    <img src="{{ asset('storage/' . $company->logo) }}" 
                                         alt="{{ $company->name }}" 
                                         class="h-8 w-auto object-contain opacity-60 hover:opacity-100 transition"
                                         title="{{ $company->name }}">
                                @else
                                    <div class="flex items-center justify-center h-8 px-3 bg-gray-100 rounded-lg opacity-60 hover:opacity-100 transition">
                                        <span class="text-xs font-semibold text-gray-600">{{ $company->name }}</span>
                                    </div>
                                @endif
                            @empty
                                <div class="flex flex-wrap gap-6 justify-center lg:justify-start">
                                    <div class="h-8 px-3 bg-gray-100 rounded-lg flex items-center">
                                        <span class="text-xs font-semibold text-gray-600">Entreprise 1</span>
                                    </div>
                                    <div class="h-8 px-3 bg-gray-100 rounded-lg flex items-center">
                                        <span class="text-xs font-semibold text-gray-600">Entreprise 2</span>
                                    </div>
                                    <div class="h-8 px-3 bg-gray-100 rounded-lg flex items-center">
                                        <span class="text-xs font-semibold text-gray-600">Entreprise 3</span>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <div class="relative hidden lg:block">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <img src="{{ asset('assets/images/carousel/1.png') }}" alt="Dashboard Barayoro" class="w-full">
                    </div>
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 gradient-bg rounded-full opacity-20 blur-2xl"></div>
                    <div class="absolute -top-6 -right-6 w-32 h-32 bg-orange-200 rounded-full opacity-50 blur-2xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Clients Section - Partenaires -->
    <section class="py-16 bg-white border-y border-gray-100">
        <div class="container mx-auto px-4 md:px-6">
            <p class="text-center text-gray-500 mb-8">Ils nous font confiance</p>
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12">
                @php
                    $trustedCompanies = \App\Models\Company::where('is_active', true)
                        ->inRandomOrder()
                        ->take(8)
                        ->get();
                @endphp
                
                @forelse($trustedCompanies as $company)
                    @if($company->logo && Storage::disk('public')->exists($company->logo))
                        <img src="{{ asset('storage/' . $company->logo) }}" 
                             alt="{{ $company->name }}" 
                             class="h-10 w-auto object-contain opacity-60 hover:opacity-100 transition grayscale hover:grayscale-0"
                             title="{{ $company->name }}">
                    @else
                        <div class="flex items-center justify-center h-10 px-5 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200 hover:border-orange-200 hover:shadow-md transition-all duration-300">
                            <span class="text-sm font-semibold text-gray-700 hover:text-orange-600 transition">
                                {{ $company->name }}
                            </span>
                        </div>
                    @endif
                @empty
                    <div class="flex flex-wrap gap-8 justify-center">
                        <div class="h-10 px-5 bg-gray-100 rounded-xl flex items-center"><span class="text-sm font-semibold text-gray-600">TechCorp Mali</span></div>
                        <div class="h-10 px-5 bg-gray-100 rounded-xl flex items-center"><span class="text-sm font-semibold text-gray-600">AfriBusiness</span></div>
                        <div class="h-10 px-5 bg-gray-100 rounded-xl flex items-center"><span class="text-sm font-semibold text-gray-600">Digital Solution</span></div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-orange-custom font-semibold uppercase tracking-wide">Fonctionnalités</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mt-2 mb-4">
                    {{ $featuresTitle ?? 'Tout ce dont votre entreprise a besoin' }}
                </h2>
                <p class="text-xl text-gray-600">
                    {{ $featuresSubtitle ?? 'Une solution complète pour gérer l\'ensemble de vos activités professionnelles' }}
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($features ?? [] as $feature)
                <div class="bg-white rounded-2xl p-8 shadow-lg card-hover">
                    <div class="w-14 h-14 gradient-bg rounded-xl flex items-center justify-center mb-5">
                        <i class="{{ $feature->icon ?? 'las la-cogs' }} text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $feature->title }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $feature->description }}</p>
                </div>
                @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">Aucune fonctionnalité disponible pour le moment.</p>
                </div>
                @endforelse
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('features') }}" class="inline-flex items-center gap-2 text-orange-custom font-semibold hover:gap-3 transition">
                    Découvrir toutes les fonctionnalités
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Jobs Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-orange-custom font-semibold uppercase tracking-wide">Opportunités</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mt-2 mb-4">
                    Offres d'emploi
                </h2>
                <p class="text-xl text-gray-600">
                    Rejoignez une équipe dynamique et participez à notre croissance
                </p>
            </div>
            
            <div class="space-y-4 max-w-4xl mx-auto">
                @forelse($jobOffers ?? [] as $job)
                <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition group">
                    <div class="flex flex-wrap justify-between items-start gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-3 py-1 bg-orange-100 text-orange-custom rounded-full text-sm font-semibold">
                                    {{ $job->contract_type ?? $job->type_contrat ?? 'CDI' }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $job->location ?? 'Bamako, Mali' }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-building mr-1"></i>{{ $job->company->name ?? 'Barayoro' }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-orange-custom transition">
                                <a href="{{ route('jobs.details', $job->id) }}">{{ $job->title }}</a>
                            </h3>
                            <p class="text-gray-600">{{ Str::limit(strip_tags($job->description ?? ''), 100) }}</p>
                        </div>
                        <a href="{{ route('jobs.details', $job->id) }}" class="px-6 py-2 border border-orange-custom text-orange-custom rounded-lg hover:bg-orange-custom hover:text-white transition whitespace-nowrap">
                            Postuler
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 bg-gray-50 rounded-xl">
                    <i class="fas fa-briefcase text-5xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Aucune offre d'emploi disponible pour le moment.</p>
                </div>
                @endforelse
            </div>
            
            <div class="text-center mt-8">
                <a href="{{ route('jobs.list') }}" class="inline-flex items-center gap-2 text-orange-custom font-semibold hover:gap-3 transition">
                    Voir toutes les offres
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-orange-custom font-semibold uppercase tracking-wide">Tarifs</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mt-2 mb-4">
                    Des formules pour tous les besoins
                </h2>
                <p class="text-xl text-gray-600">
                    Choisissez le plan qui correspond à votre activité. Sans engagement.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @forelse($pricingPlans ?? [] as $plan)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition {{ $plan->is_popular ? 'ring-2 ring-orange-custom transform scale-105' : '' }}">
                    @if($plan->is_popular)
                    <div class="bg-orange-custom text-white text-center py-2 text-sm font-semibold">
                        🔥 Le plus populaire
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
                            @php
                                $features = $plan->features;
                                if (is_string($features)) { $features = json_decode($features, true); }
                                if (!is_array($features)) { $features = []; }
                            @endphp
                            @foreach($features as $feature)
                            <li class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span class="text-gray-600">{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">Aucun plan tarifaire disponible pour le moment.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Avis Clients Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-orange-custom font-semibold uppercase tracking-wide">Avis Clients</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mt-2 mb-4">
                    Ce que nos utilisateurs disent de Barayoro
                </h2>
                <p class="text-xl text-gray-600">
                    Découvrez les retours d'expérience des entreprises qui numérisent leur quotidien avec nous.
                </p>
            </div>

            @php
                $approvedReviews = \App\Models\Review::approved()->with('user')->latest()->take(3)->get();
            @endphp

            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto mb-16">
                @forelse($approvedReviews as $pubReview)
                    <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 flex flex-col justify-between shadow-sm hover:shadow-md transition">
                        <div>
                            <div class="flex text-yellow-400 mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $pubReview->rating ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                            </div>
                            <h4 class="text-lg font-bold text-gray-800 mb-2">« {{ $pubReview->title }} »</h4>
                            <p class="text-gray-600 italic leading-relaxed mb-6">
                                "{{ Str::limit($pubReview->content, 180) }}"
                            </p>
                        </div>
                        <div class="flex items-center gap-3 pt-4 border-t border-gray-200/60">
                            <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-custom font-bold flex items-center justify-center uppercase text-sm">
                                {{ Str::substr($pubReview->user->name ?? 'C', 0, 2) }}
                            </div>
                            <div>
                                <h5 class="font-bold text-gray-800 text-sm">{{ $pubReview->user->name ?? 'Client Anonyme' }}</h5>
                                <p class="text-xs text-gray-400">Utilisateur vérifié</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100">
                        <div class="flex text-yellow-400 mb-4"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                        <h4 class="text-lg font-bold text-gray-800 mb-2">« Outil indispensable ! »</h4>
                        <p class="text-gray-600 italic leading-relaxed mb-6">"La gestion hors-ligne a changé notre logistique à Bamako. On synchronise tout dès qu'on capte."</p>
                        <h5 class="font-bold text-gray-800 text-sm">Moussa Diarra</h5>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100">
                        <div class="flex text-yellow-400 mb-4"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                        <h4 class="text-lg font-bold text-gray-800 mb-2">« Facturation claire »</h4>
                        <p class="text-gray-600 italic leading-relaxed mb-6">"Les paiements Orange Money intégrés nous font gagner un temps précieux sur la relance des factures."</p>
                        <h5 class="font-bold text-gray-800 text-sm">Mariam B.</h5>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100">
                        <div class="flex text-yellow-400 mb-4"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="font-regular far fa-star"></i></div>
                        <h4 class="text-lg font-bold text-gray-800 mb-2">« Suivi d'équipe au top »</h4>
                        <p class="text-gray-600 italic leading-relaxed mb-6">"Le Kanban intégré centralise tous nos projets de sous-traitance. Très satisfait."</p>
                        <h5 class="font-bold text-gray-800 text-sm">Alou Traoré</h5>
                    </div>
                @endforelse
            </div>

            <!-- Intégration du formulaire d'envoi d'avis général -->
            <div class="max-w-3xl mx-auto">
                @include('partials.reviews-form', [
                    'reviewableType' => 'App\Models\Company', 
                    'reviewableId' => 1
                ])
            </div>
        </div>
    </section>

    <!-- Contact Section / CTA -->
    <section class="py-20 gradient-bg">
        <div class="container mx-auto px-4 md:px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Prêt à transformer votre entreprise ?
            </h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                Rejoignez plus de {{ number_format($totalCompaniesCount ?? 0) }} entreprises qui utilisent Barayoro au quotidien
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-orange-custom rounded-xl font-semibold hover:shadow-xl transition transform hover:-translate-y-1">
                    Commencer l'essai gratuit
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-4 border-2 border-white text-white rounded-xl font-semibold hover:bg-white hover:text-orange-custom transition">
                    Nous contacter
                </a>
            </div>
        </div>
    </section>
@endsection