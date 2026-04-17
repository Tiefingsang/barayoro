<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    <title>Barayoro - Solution SaaS de gestion d'entreprise | Par Masadigitale</title>
    <meta name="description" content="Barayoro est la solution SaaS tout-en-un pour gérer vos ventes, factures, stocks, projets et équipes. Accès hors ligne, synchronisation automatique. Essayez gratuitement pendant 30 jours.">
    <meta name="keywords" content="gestion d'entreprise, SaaS, facturation, stock, projets, CRM, ERP, Afrique, Sénégal, Masadigitale">
    <meta name="author" content="Masadigitale">
    <meta name="robots" content="index, follow">
    <meta name="language" content="French">
    <meta name="revisit-after" content="7 days">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Barayoro - Solution SaaS de gestion d'entreprise">
    <meta property="og:description" content="Gérez votre entreprise simplement et efficacement avec Barayoro. Facturation, stocks, projets, équipes. Essai gratuit 30 jours.">
    <meta property="og:image" content="{{ asset('assets/images/og-image.jpg') }}">
    <meta property="og:site_name" content="Barayoro">
    <meta property="og:locale" content="fr_FR">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url('/') }}">
    <meta name="twitter:title" content="Barayoro - Solution SaaS de gestion d'entreprise">
    <meta name="twitter:description" content="Gérez votre entreprise simplement et efficacement avec Barayoro. Essai gratuit 30 jours.">
    <meta name="twitter:image" content="{{ asset('assets/images/twitter-image.jpg') }}">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url('/') }}">
    
    <!-- Alternate languages -->
    <link rel="alternate" hreflang="fr" href="{{ url('/') }}">
    <link rel="alternate" hreflang="en" href="{{ url('/en') }}">
    
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#ff6c00">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Barayoro">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('icons/icon-512x512.png') }}">
    
    <!-- Preload critical resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Structured Data / Schema.org -->
    
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Line Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .bg-orange-custom { background-color: #ff6c00; }
        .text-orange-custom { color: #ff6c00; }
        .border-orange-custom { border-color: #ff6c00; }
        .hover-bg-orange:hover { background-color: #ff6c00; }
        .hover-text-orange:hover { color: #ff6c00; }
        .gradient-bg {
            background: linear-gradient(135deg, #ff6c00 0%, #e05a00 100%);
        }
        .btn-primary {
            background: linear-gradient(135deg, #ff6c00 0%, #e05a00 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(255, 108, 0, 0.3);
        }
        .btn-outline {
            border: 1px solid #ff6c00;
            color: #ff6c00;
            transition: all 0.3s ease;
        }
        .btn-outline:hover {
            background-color: #ff6c00;
            color: white;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .animate-fade-in {
            animation: fadeIn 1s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .hero-section {
            background: linear-gradient(135deg, #fff5eb 0%, #ffe8d9 100%);
        }
    </style>
</head>
<body class="bg-white">

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-md shadow-sm z-50 transition-all duration-300">
        <div class="container mx-auto px-4 md:px-6 py-4">
            <div class="flex flex-wrap justify-between items-center">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2" aria-label="Barayoro - Accueil">
                    <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-xl">B</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-800">Barayoro</span>
                    <span class="text-xs text-orange-custom font-semibold ml-1">by Masadigitale</span>
                </a>

                <!-- Menu Desktop -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#accueil" class="text-gray-600 hover:text-orange-custom transition duration-300">Accueil</a>
                    <a href="#apropos" class="text-gray-600 hover:text-orange-custom transition duration-300">À propos</a>
                    <a href="#fonctionnalites" class="text-gray-600 hover:text-orange-custom transition duration-300">Fonctionnalités</a>
                    <a href="#offres" class="text-gray-600 hover:text-orange-custom transition duration-300">Offres d'emploi</a>
                    <a href="#tarifs" class="text-gray-600 hover:text-orange-custom transition duration-300">Tarifs</a>
                    <a href="#documentation" class="text-gray-600 hover:text-orange-custom transition duration-300">Documentation</a>
                    <a href="#contact" class="text-gray-600 hover:text-orange-custom transition duration-300">Contact</a>
                </div>

                <!-- Boutons connexion/inscription -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2 bg-orange-custom text-white rounded-lg hover:bg-orange-700 transition">
                            <i class="fas fa-tachometer-alt mr-2"></i>Tableau de bord
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2 text-orange-custom border border-orange-custom rounded-lg hover:bg-orange-custom hover:text-white transition">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2 gradient-bg text-white rounded-lg btn-primary">
                            Essai gratuit
                        </a>
                    @endauth
                </div>

                <!-- Menu Mobile -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-gray-600 focus:outline-none" aria-label="Menu">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>

            <!-- Menu Mobile Dropdown -->
            <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4 space-y-3">
                <a href="#accueil" class="block text-gray-600 hover:text-orange-custom transition">Accueil</a>
                <a href="#apropos" class="block text-gray-600 hover:text-orange-custom transition">À propos</a>
                <a href="#fonctionnalites" class="block text-gray-600 hover:text-orange-custom transition">Fonctionnalités</a>
                <a href="#offres" class="block text-gray-600 hover:text-orange-custom transition">Offres d'emploi</a>
                <a href="#tarifs" class="block text-gray-600 hover:text-orange-custom transition">Tarifs</a>
                <a href="#documentation" class="block text-gray-600 hover:text-orange-custom transition">Documentation</a>
                <a href="#contact" class="block text-gray-600 hover:text-orange-custom transition">Contact</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 bg-orange-custom text-white rounded-lg text-center">Tableau de bord</a>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2 text-orange-custom border border-orange-custom rounded-lg text-center">Connexion</a>
                    <a href="{{ route('register') }}" class="block px-4 py-2 gradient-bg text-white rounded-lg text-center">Essai gratuit</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
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
                        Barayoro est la solution SaaS tout-en-un pour gérer vos ventes, factures, stocks, 
                        projets et équipes. Accédez à vos données partout, même hors ligne.
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
                            <img src="https://randomuser.me/api/portraits/women/1.jpg" class="w-10 h-10 rounded-full border-2 border-white" alt="Client">
                            <img src="https://randomuser.me/api/portraits/men/2.jpg" class="w-10 h-10 rounded-full border-2 border-white" alt="Client">
                            <img src="https://randomuser.me/api/portraits/women/3.jpg" class="w-10 h-10 rounded-full border-2 border-white" alt="Client">
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 border-2 border-white">+2k</div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Plus de <span class="font-bold text-orange-custom">2 000+</span> entreprises nous font confiance</p>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-orange-500/20 to-orange-400/20 rounded-full blur-3xl"></div>
                    <img src="{{ asset('assets/images/hero-illustration.png') }}" alt="Barayoro Dashboard - Interface de gestion d'entreprise" class="relative z-10 rounded-2xl shadow-2xl w-full">
                </div>
            </div>
        </div>
    </section>

    <!-- Clients section -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4 md:px-6">
            <p class="text-center text-gray-500 mb-8">Ils nous font confiance</p>
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12 opacity-60">
                <img src="{{ asset('assets/images/logo-placeholder-1.png') }}" alt="Client" class="h-8 grayscale hover:grayscale-0 transition">
                <img src="{{ asset('assets/images/logo-placeholder-2.png') }}" alt="Client" class="h-8 grayscale hover:grayscale-0 transition">
                <img src="{{ asset('assets/images/logo-placeholder-3.png') }}" alt="Client" class="h-8 grayscale hover:grayscale-0 transition">
                <img src="{{ asset('assets/images/logo-placeholder-4.png') }}" alt="Client" class="h-8 grayscale hover:grayscale-0 transition">
                <img src="{{ asset('assets/images/logo-placeholder-5.png') }}" alt="Client" class="h-8 grayscale hover:grayscale-0 transition">
            </div>
        </div>
    </section>

    <!-- Fonctionnalités -->
    <section id="fonctionnalites" class="py-20">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-orange-custom font-semibold uppercase tracking-wide">Fonctionnalités</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mt-2 mb-4">
                    Tout ce dont votre entreprise a besoin
                </h2>
                <p class="text-gray-600">
                    Une solution complète pour gérer l'ensemble de vos activités professionnelles
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover border border-gray-100">
                    <div class="w-14 h-14 gradient-bg rounded-xl flex items-center justify-center mb-4">
                        <i class="las la-chart-line text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Tableau de bord analytique</h3>
                    <p class="text-gray-600">Visualisez vos KPIs en temps réel avec des graphiques interactifs.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover border border-gray-100">
                    <div class="w-14 h-14 gradient-bg rounded-xl flex items-center justify-center mb-4">
                        <i class="las la-file-invoice text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Facturation & Devis</h3>
                    <p class="text-gray-600">Créez des factures professionnelles et gérez vos paiements.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover border border-gray-100">
                    <div class="w-14 h-14 gradient-bg rounded-xl flex items-center justify-center mb-4">
                        <i class="las la-boxes text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Gestion de stock</h3>
                    <p class="text-gray-600">Suivez vos produits et recevez des alertes de stock faible.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover border border-gray-100">
                    <div class="w-14 h-14 gradient-bg rounded-xl flex items-center justify-center mb-4">
                        <i class="las la-project-diagram text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Gestion de projets</h3>
                    <p class="text-gray-600">Organisez vos projets et suivez l'avancement des tâches.</p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover border border-gray-100">
                    <div class="w-14 h-14 gradient-bg rounded-xl flex items-center justify-center mb-4">
                        <i class="las la-users text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Gestion des équipes</h3>
                    <p class="text-gray-600">Gérez vos collaborateurs et définissez leurs rôles.</p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover border border-gray-100">
                    <div class="w-14 h-14 gradient-bg rounded-xl flex items-center justify-center mb-4">
                        <i class="las la-mobile-alt text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Mode hors ligne</h3>
                    <p class="text-gray-600">Travaillez sans connexion, synchronisation automatique.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section À propos -->
    <section id="apropos" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <img src="{{ asset('assets/images/about-illustration.png') }}" alt="Barayoro - Solution Masadigitale" class="rounded-2xl shadow-xl w-full">
                </div>
                <div>
                    <span class="text-orange-custom font-semibold uppercase tracking-wide">À propos</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mt-2 mb-4">
                        Barayoro, par <span class="text-orange-custom">Masadigitale</span>
                    </h2>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Barayoro est une solution innovante développée par <strong class="text-orange-custom">Masadigitale</strong>, 
                        un leader africain des solutions digitales. Notre mission est de fournir aux entreprises 
                        africaines un outil de gestion moderne, accessible et performant.
                    </p>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="las la-check-circle text-orange-custom text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold">Solution complète</h4>
                            <p class="text-sm text-gray-500">Tous les outils en un seul endroit</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="las la-cloud-upload-alt text-orange-custom text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold">Synchronisation cloud</h4>
                            <p class="text-sm text-gray-500">Accès partout, même hors ligne</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Offres d'emploi -->
    <section id="offres" class="py-20">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-orange-custom font-semibold uppercase tracking-wide">Carrières</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mt-2 mb-4">
                    Rejoignez notre équipe
                </h2>
                <p class="text-gray-600">
                    Nous recherchons des talents passionnés pour nous aider à révolutionner la gestion d'entreprise
                </p>
            </div>

            <div class="max-w-3xl mx-auto space-y-4">
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition border-l-4 border-orange-custom">
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Développeur Full Stack Laravel</h3>
                            <p class="text-gray-500 mt-1">Dakar, Sénégal / Télétravail partiel</p>
                        </div>
                        <a href="#" class="px-5 py-2 btn-outline rounded-lg transition font-semibold">Postuler</a>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition border-l-4 border-orange-custom">
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Commercial B2B</h3>
                            <p class="text-gray-500 mt-1">Abidjan, Côte d'Ivoire</p>
                        </div>
                        <a href="#" class="px-5 py-2 btn-outline rounded-lg transition font-semibold">Postuler</a>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition border-l-4 border-orange-custom">
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">UX/UI Designer</h3>
                            <p class="text-gray-500 mt-1">Paris, France / Remote</p>
                        </div>
                        <a href="#" class="px-5 py-2 btn-outline rounded-lg transition font-semibold">Postuler</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Tarifs -->
    <section id="tarifs" class="py-20 bg-gray-50">
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

            <div class="grid md:grid-cols-2 lg:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Plan Essai Gratuit -->
                <div class="bg-white rounded-2xl p-8 shadow-xl relative overflow-hidden card-hover">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-orange-100 rounded-full -mr-16 -mt-16 opacity-20"></div>
                    <div class="mb-6">
                        <div class="w-16 h-16 gradient-bg rounded-2xl flex items-center justify-center mb-4">
                            <i class="las la-gem text-2xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Essai gratuit</h3>
                        <p class="text-gray-500 mt-1">Idéal pour découvrir la plateforme</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-4xl font-bold">0€</span>
                        <span class="text-gray-500">/30 jours</span>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2"><i class="las la-check-circle text-orange-custom"></i> 5 utilisateurs max</li>
                        <li class="flex items-center gap-2"><i class="las la-check-circle text-orange-custom"></i> Toutes les fonctionnalités</li>
                        <li class="flex items-center gap-2"><i class="las la-check-circle text-orange-custom"></i> Support par email</li>
                        <li class="flex items-center gap-2"><i class="las la-check-circle text-orange-custom"></i> 30 jours d'essai</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full text-center py-3 btn-outline rounded-lg transition font-semibold">
                        Commencer l'essai
                    </a>
                </div>

                <!-- Plan Premium Annuel -->
                <div class="bg-white rounded-2xl p-8 shadow-xl relative overflow-hidden card-hover border-2 border-orange-custom">
                    <div class="absolute top-0 right-0 bg-orange-custom text-white px-4 py-1 rounded-bl-2xl text-sm font-semibold">Populaire</div>
                    <div class="mb-6">
                        <div class="w-16 h-16 gradient-bg rounded-2xl flex items-center justify-center mb-4">
                            <i class="las la-crown text-2xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Premium Annuel</h3>
                        <p class="text-gray-500 mt-1">Pour les entreprises en croissance</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-4xl font-bold">490€</span>
                        <span class="text-gray-500">/an</span>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2"><i class="las la-check-circle text-orange-custom"></i> Utilisateurs illimités</li>
                        <li class="flex items-center gap-2"><i class="las la-check-circle text-orange-custom"></i> Toutes les fonctionnalités</li>
                        <li class="flex items-center gap-2"><i class="las la-check-circle text-orange-custom"></i> Support prioritaire 24/7</li>
                        <li class="flex items-center gap-2"><i class="las la-check-circle text-orange-custom"></i> API dédiée</li>
                        <li class="flex items-center gap-2"><i class="las la-check-circle text-orange-custom"></i> Formation incluse</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full text-center py-3 gradient-bg text-white rounded-lg btn-primary font-semibold">
                        Choisir Premium
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Documentation -->
    <section id="documentation" class="py-20">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-orange-custom font-semibold uppercase tracking-wide">Ressources</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mt-2 mb-4">
                    Documentation et support
                </h2>
                <p class="text-gray-600">
                    Tout ce dont vous avez besoin pour maîtriser Barayoro
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-gray-50 rounded-xl p-6 text-center card-hover">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="las la-file-alt text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Guide utilisateur</h3>
                    <p class="text-gray-600 mb-4">Documentation complète pour bien démarrer</p>
                    <a href="#" class="text-orange-custom hover:underline">Lire la documentation →</a>
                </div>
                <div class="bg-gray-50 rounded-xl p-6 text-center card-hover">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="las la-video text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Tutoriels vidéo</h3>
                    <p class="text-gray-600 mb-4">Apprenez pas à pas avec nos vidéos</p>
                    <a href="#" class="text-orange-custom hover:underline">Voir les tutoriels →</a>
                </div>
                <div class="bg-gray-50 rounded-xl p-6 text-center card-hover">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="las la-question-circle text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">FAQ</h3>
                    <p class="text-gray-600 mb-4">Réponses à vos questions fréquentes</p>
                    <a href="#" class="text-orange-custom hover:underline">Consulter la FAQ →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Contact -->
    <section id="contact" class="py-20 bg-gray-900 text-white">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid md:grid-cols-2 gap-12">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Prêt à transformer votre entreprise ?</h2>
                    <p class="text-gray-300 mb-6 leading-relaxed">
                        Rejoignez plus de 2 000 entreprises qui utilisent déjà Barayoro pour gérer leurs opérations quotidiennes.
                    </p>
                    <div class="flex items-center gap-4 mb-8">
                        <div class="flex -space-x-2">
                            <div class="w-12 h-12 rounded-full bg-orange-custom flex items-center justify-center text-white font-bold">+2k</div>
                        </div>
                        <p class="text-gray-300">Entreprises nous font confiance</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="px-8 py-3 gradient-bg text-white rounded-lg btn-primary text-center font-semibold">
                            Commencer l'essai gratuit
                        </a>
                        <a href="#contact" class="px-8 py-3 border border-gray-600 rounded-lg hover:bg-gray-800 transition text-center">
                            Contactez-nous
                        </a>
                    </div>
                </div>
                <div>
                    <form class="bg-gray-800 rounded-2xl p-6">
                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm mb-2">Nom complet</label>
                                <input type="text" class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-custom" placeholder="Votre nom">
                            </div>
                            <div>
                                <label class="block text-sm mb-2">Email</label>
                                <input type="email" class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-custom" placeholder="votre@email.com">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-2">Message</label>
                            <textarea rows="4" class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-custom" placeholder="Votre message..."></textarea>
                        </div>
                        <button type="submit" class="w-full py-3 gradient-bg text-white rounded-lg btn-primary font-semibold">
                            Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 pt-16 pb-8 border-t border-gray-800">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-xl">B</span>
                        </div>
                        <span class="text-2xl font-bold text-white">Barayoro</span>
                    </div>
                    <p class="text-gray-400 text-sm mb-4">
                        La solution SaaS complète pour la gestion d'entreprise en Afrique.
                    </p>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-500 text-sm">Une création</span>
                        <a href="https://masadigitale.com" target="_blank" class="text-orange-custom font-semibold hover:underline">Masadigitale</a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Liens rapides</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#accueil" class="hover:text-orange-custom transition">Accueil</a></li>
                        <li><a href="#apropos" class="hover:text-orange-custom transition">À propos</a></li>
                        <li><a href="#fonctionnalites" class="hover:text-orange-custom transition">Fonctionnalités</a></li>
                        <li><a href="#tarifs" class="hover:text-orange-custom transition">Tarifs</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#documentation" class="hover:text-orange-custom transition">Documentation</a></li>
                        <li><a href="#" class="hover:text-orange-custom transition">Centre d'aide</a></li>
                        <li><a href="#" class="hover:text-orange-custom transition">Contact</a></li>
                        <li><a href="#" class="hover:text-orange-custom transition">Statut</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Légal</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('terms') }}" class="hover:text-orange-custom transition">Conditions d'utilisation</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:text-orange-custom transition">Confidentialité</a></li>
                        <li><a href="#" class="hover:text-orange-custom transition">Cookies</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-gray-500 text-sm">
                    &copy; {{ date('Y') }} Barayoro. Tous droits réservés. 
                    Développé avec ❤️ par <a href="https://masadigitale.com" target="_blank" class="text-orange-custom hover:underline">Masadigitale</a>
                </p>
                <p class="text-gray-600 text-xs mt-2">
                    Barayoro est une marque déposée de Masadigitale. Toutes les autres marques sont la propriété de leurs détenteurs respectifs.
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                    document.getElementById('mobile-menu')?.classList.add('hidden');
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-md');
            } else {
                nav.classList.remove('shadow-md');
            }
        });
    </script>
</body>
</html>