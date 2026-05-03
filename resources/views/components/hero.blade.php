<section id="accueil" class="hero-section pt-32 pb-20 md:pt-40 md:pb-28">
    <div class="container mx-auto px-4 md:px-6">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="animate-fade-in">
                <div class="inline-flex items-center gap-2 bg-orange-100 text-orange-custom px-4 py-2 rounded-full mb-6">
                    <i class="fas fa-certificate text-sm"></i>
                    <span class="text-sm font-semibold">Par Masadigitale</span>
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 leading-tight mb-6">
                    Gérez votre entreprise
                    <span class="gradient-bg bg-clip-text text-transparent">simplement</span>
                    et <span class="text-orange-custom">efficacement</span>
                </h1>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    {{ $settings['hero_description'] ?? 'Barayoro est la solution SaaS tout-en-un pour gérer vos ventes, factures, stocks, projets et équipes.' }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('register') }}" class="px-8 py-3 gradient-bg text-white rounded-lg btn-primary text-center font-semibold">
                        <i class="fas fa-rocket mr-2"></i>Démarrer l'essai gratuit
                    </a>
                    <a href="#fonctionnalites" class="px-8 py-3 btn-outline rounded-lg text-center font-semibold">
                        <i class="fas fa-play mr-2"></i>Voir la démo
                    </a>
                </div>
                <div class="flex items-center gap-6 mt-8">
                    <div class="flex -space-x-2">
                        @foreach($trustedCompanies->take(3) as $company)
                            <img src="{{ $company->logo_url ?? 'https://randomuser.me/api/portraits/women/1.jpg' }}" 
                                 class="w-10 h-10 rounded-full border-2 border-white" 
                                 alt="{{ $company->name }}">
                        @endforeach
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 border-2 border-white">
                            +{{ $totalCompaniesCount }}
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Plus de <span class="font-bold text-orange-custom">{{ $totalCompaniesCount }}+</span> entreprises nous font confiance</p>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-r from-orange-500/20 to-orange-400/20 rounded-full blur-3xl"></div>
                <img src="{{ asset('assets/images/hero-illustration.png') }}" alt="Barayoro Dashboard" class="relative z-10 rounded-2xl shadow-2xl w-full">
            </div>
        </div>
    </div>
</section>