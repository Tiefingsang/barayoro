@extends('layouts.master')

@section('title', 'À propos - Barayoro')
@section('description', 'Découvrez Barayoro, la solution SaaS de gestion d\'entreprise développée par Masadigitale.')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <section class="hero-section pt-32 pb-20 md:pt-40 md:pb-28">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 mb-6">
                    À propos de <span class="text-orange-custom">Barayoro</span>
                </h1>
                <p class="text-xl text-gray-600 leading-relaxed">
                    Barayoro est une solution SaaS innovante développée par Masadigitale, 
                    conçue pour aider les entreprises africaines à gérer efficacement leurs opérations quotidiennes.
                </p>
            </div>
        </div>
    </section>

    <!-- Notre mission -->
    <section class="py-20">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-orange-custom font-semibold uppercase tracking-wide">Notre mission</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mt-2 mb-6">
                        Simplifier la gestion d'entreprise en Afrique
                    </h2>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Notre mission est de fournir aux entreprises africaines un outil de gestion moderne, 
                        accessible et performant. Nous croyons que la technologie peut transformer la façon 
                        dont les entreprises opèrent au quotidien.
                    </p>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Avec Barayoro, nous avons créé une solution complète qui répond aux besoins spécifiques 
                        des entreprises du continent : facturation adaptée, gestion de stock, suivi de projets, 
                        et bien plus encore.
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="las la-chart-line text-orange-custom text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">+2000 entreprises</h4>
                            <p class="text-sm text-gray-500">Nous font confiance</p>
                        </div>
                    </div>
                </div>
                <div>
                    <img src="{{ asset('assets/images/about-mission.jpg') }}" 
                         alt="Notre mission" 
                         class="rounded-2xl shadow-xl w-full">
                </div>
            </div>
        </div>
    </section>

    <!-- Notre histoire -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-orange-custom font-semibold uppercase tracking-wide">Notre histoire</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mt-2 mb-4">
                    L'histoire derrière Barayoro
                </h2>
                <p class="text-gray-600">
                    Une aventure humaine et technologique au service des entreprises
                </p>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="relative pl-8 border-l-2 border-orange-custom space-y-12">
                    <div class="relative">
                        <div class="absolute -left-10 w-6 h-6 bg-orange-custom rounded-full"></div>
                        <div class="bg-white rounded-xl p-6 shadow-md">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">2021 - La genèse</h3>
                            <p class="text-gray-600">
                                Masadigitale identifie un besoin croissant des entreprises africaines pour des 
                                outils de gestion adaptés à leurs réalités locales.
                            </p>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <div class="absolute -left-10 w-6 h-6 bg-orange-custom rounded-full"></div>
                        <div class="bg-white rounded-xl p-6 shadow-md">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">2022 - Développement</h3>
                            <p class="text-gray-600">
                                Lancement du projet Barayoro avec une équipe passionnée de développeurs 
                                et d'experts en gestion d'entreprise.
                            </p>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <div class="absolute -left-10 w-6 h-6 bg-orange-custom rounded-full"></div>
                        <div class="bg-white rounded-xl p-6 shadow-md">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">2023 - Lancement officiel</h3>
                            <p class="text-gray-600">
                                Barayoro est officiellement lancé et séduit rapidement de nombreuses entreprises 
                                grâce à sa simplicité et son efficacité.
                            </p>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <div class="absolute -left-10 w-6 h-6 bg-orange-custom rounded-full"></div>
                        <div class="bg-white rounded-xl p-6 shadow-md">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">2024 - Expansion</h3>
                            <p class="text-gray-600">
                                Fort de son succès, Barayoro s'étend à plusieurs pays d'Afrique de l'Ouest 
                                et continue d'innover.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Nos valeurs -->
    <section class="py-20">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-orange-custom font-semibold uppercase tracking-wide">Nos valeurs</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mt-2 mb-4">
                    Ce qui nous guide
                </h2>
                <p class="text-gray-600">
                    Des valeurs qui façonnent notre façon de travailler et de servir nos clients
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="w-20 h-20 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="las la-star text-3xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Excellence</h3>
                    <p class="text-gray-600">
                        Nous visons l'excellence dans tout ce que nous faisons, de la qualité de notre produit 
                        à la satisfaction de nos clients.
                    </p>
                </div>
                
                <div class="text-center p-6">
                    <div class="w-20 h-20 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="las la-lightbulb text-3xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Innovation</h3>
                    <p class="text-gray-600">
                        Nous innovons constamment pour offrir les meilleures solutions à nos clients 
                        et anticiper leurs besoins.
                    </p>
                </div>
                
                <div class="text-center p-6">
                    <div class="w-20 h-20 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="las la-handshake text-3xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Confiance</h3>
                    <p class="text-gray-600">
                        Nous construisons des relations de confiance durable avec nos clients, 
                        basées sur la transparence et le respect.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- L'équipe -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-orange-custom font-semibold uppercase tracking-wide">L'équipe</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mt-2 mb-4">
                    Derrière Barayoro
                </h2>
                <p class="text-gray-600">
                    Une équipe passionnée dédiée à votre réussite
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <img src="{{ asset('assets/images/team/ceo.jpg') }}" 
                         alt="CEO" 
                         class="w-40 h-40 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="text-xl font-bold text-gray-800">Mamadou Diallo</h3>
                    <p class="text-orange-custom mb-2">CEO & Fondateur</p>
                    <p class="text-gray-500 text-sm">Ancien consultant en gestion d'entreprise</p>
                </div>
                
                <div class="text-center">
                    <img src="{{ asset('assets/images/team/cto.jpg') }}" 
                         alt="CTO" 
                         class="w-40 h-40 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="text-xl font-bold text-gray-800">Aïssatou Sow</h3>
                    <p class="text-orange-custom mb-2">CTO</p>
                    <p class="text-gray-500 text-sm">Expert en développement Laravel</p>
                </div>
                
                <div class="text-center">
                    <img src="{{ asset('assets/images/team/cmo.jpg') }}" 
                         alt="CMO" 
                         class="w-40 h-40 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="text-xl font-bold text-gray-800">Ibrahima Ndiaye</h3>
                    <p class="text-orange-custom mb-2">CMO</p>
                    <p class="text-gray-500 text-sm">Spécialiste en marketing digital</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Chiffres clés -->
    <section class="py-20 gradient-bg text-white">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Barayoro en chiffres</h2>
                <p class="text-orange-100">
                    Quelques chiffres qui témoignent de notre engagement
                </p>
            </div>

            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl md:text-5xl font-bold mb-2">{{ $stats['years_experience'] ?? 5 }}+</div>
                    <p class="text-orange-100">Années d'expérience</p>
                </div>
                <div>
                    <div class="text-4xl md:text-5xl font-bold mb-2">{{ $stats['customers'] ?? 2000 }}+</div>
                    <p class="text-orange-100">Entreprises clientes</p>
                </div>
                <div>
                    <div class="text-4xl md:text-5xl font-bold mb-2">{{ $stats['countries'] ?? 8 }}</div>
                    <p class="text-orange-100">Pays couverts</p>
                </div>
                <div>
                    <div class="text-4xl md:text-5xl font-bold mb-2">{{ $stats['projects'] ?? 5000 }}+</div>
                    <p class="text-orange-100">Projets gérés</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-20">
        <div class="container mx-auto px-4 md:px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                Prêt à rejoindre l'aventure ?
            </h2>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                Découvrez comment Barayoro peut transformer la gestion de votre entreprise
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="px-8 py-3 gradient-bg text-white rounded-lg btn-primary font-semibold">
                    <i class="fas fa-rocket mr-2"></i>Commencer l'essai gratuit
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-3 btn-outline rounded-lg font-semibold">
                    <i class="fas fa-comment mr-2"></i>Nous contacter
                </a>
            </div>
        </div>
    </section>
</div>
@endsection