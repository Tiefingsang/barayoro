<!DOCTYPE html>
<html dir="ltr" lang="fr">
{{-- <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('assets/images/Barayoro_logo.png') }}" type="image/x-icon" />
    
    <title>@yield('title', 'Barayoro - Solution SaaS de gestion d\'entreprise')</title>
    <meta name="description" content="@yield('description', 'Barayoro est la solution SaaS tout-en-un pour gérer vos ventes, factures, stocks, projets et équipes.')" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/line-awesome/css/line-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/swiper.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <!-- PWA -->
    <link rel="manifest" href="/manifest.json" />
    <meta name="theme-color" content="#ff6c00" />
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .bg-orange-custom { background-color: #ff6c00; }
        .text-orange-custom { color: #ff6c00; }
        .border-orange-custom { border-color: #ff6c00; }
        .gradient-bg { background: linear-gradient(135deg, #ff6c00 0%, #e05a00 100%); }
        .btn-primary { background: linear-gradient(135deg, #ff6c00 0%, #e05a00 100%); transition: all 0.3s ease; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(255, 108, 0, 0.3); }
        .btn-outline { border: 1px solid #ff6c00; color: #ff6c00; transition: all 0.3s ease; }
        .btn-outline:hover { background-color: #ff6c00; color: white; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        .hero-section { background: linear-gradient(135deg, #fff5eb 0%, #ffe8d9 100%); }
        .loading { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; }
        .loading.active { display: block; }
    </style>
    
    @stack('styles')
</head> --}}

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('assets/images/Barayoro_logo.png') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset('assets/images/Barayoro_logo.png') }}" />
    
    <!-- Titre dynamique -->
    <title>@yield('title', 'Barayoro - Solution SaaS de gestion d\'entreprise pour PME africaines')</title>
    <meta name="description" content="@yield('description', 'Barayoro est la solution SaaS tout-en-un pour gérer vos ventes, factures, stocks, projets et équipes. Essai gratuit 30 jours.')" />
    <meta name="keywords" content="@yield('keywords', 'gestion entreprise, SaaS, facturation, gestion des tâches, gestion des projets, Orange Money, Wave, logiciel gestion PME, Barayoro, gestion stock')" />
    <meta name="author" content="Barayoro" />
    <meta name="robots" content="@yield('robots', 'index, follow')" />
    <meta name="googlebot" content="index, follow" />
    <meta name="bingbot" content="index, follow" />
    <meta name="language" content="French" />
    <meta name="geo.placename" content="Mali, Afrique" />
    <meta name="geo.region" content="ML" />
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og:type', 'website')" />
    <meta property="og:url" content="@yield('og:url', url()->current())" />
    <meta property="og:title" content="@yield('og:title', 'Barayoro - Solution SaaS de gestion d\'entreprise')" />
    <meta property="og:description" content="@yield('og:description', 'Barayoro est la solution SaaS tout-en-un pour gérer vos ventes, factures, stocks, projets et équipes. Essai gratuit 30 jours.')" />
    <meta property="og:image" content="@yield('og:image', asset('assets/images/barayoro-og-image.jpg'))" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:site_name" content="Barayoro" />
    <meta property="og:locale" content="fr_FR" />
    <meta property="og:locale:alternate" content="fr_FR" />
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@barayoro" />
    <meta name="twitter:creator" content="@barayoro" />
    <meta name="twitter:url" content="@yield('og:url', url()->current())" />
    <meta name="twitter:title" content="@yield('og:title', 'Barayoro - Solution SaaS de gestion d\'entreprise')" />
    <meta name="twitter:description" content="@yield('og:description', 'Barayoro est la solution SaaS tout-en-un pour gérer vos ventes, factures, stocks, projets et équipes.')" />
    <meta name="twitter:image" content="@yield('og:image', asset('assets/images/barayoro-twitter-image.jpg'))" />
    
    <!-- LinkedIn / Professional Networks -->
    <meta property="linkedin:title" content="@yield('og:title', 'Barayoro - Solution SaaS de gestion d\'entreprise')" />
    <meta property="linkedin:description" content="@yield('og:description', 'Barayoro est la solution SaaS tout-en-un pour gérer vos ventes, factures, stocks, projets et équipes.')" />
    
    <!-- WhatsApp / Telegram -->
    <meta property="whatsapp:title" content="@yield('og:title', 'Barayoro')" />
    <meta property="telegram:title" content="@yield('og:title', 'Barayoro')" />
    
    <!-- iOS / Apple -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-title" content="Barayoro" />
    <link rel="apple-touch-icon" href="{{ asset('assets/images/barayoro_logo_care.jpeg') }}" />
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/images/barayoro_logo_care.jpeg') }}" />
    <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('assets/images/barayoro_logo_care.jpeg') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/barayoro_logo_care.jpeg') }}" />
    
    <!-- Android / Chrome -->
    <link rel="manifest" href="/manifest.json" />
    <meta name="theme-color" content="#ff6c00" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="application-name" content="Barayoro" />
    
    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical', url()->current())" />
    
    <!-- Alternate language versions -->
    @yield('alternate-langs')
    
    <!-- RSS Feed -->
    <link rel="alternate" type="application/rss+xml" title="Barayoro Blog" href="{{ route('blog.index') }}" />
    
    <!-- Sitemap -->
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ route('sitemap') }}" />
    
    <!-- DNS Prefetch pour performance -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com" />
    <link rel="dns-prefetch" href="https://cdn.tailwindcss.com" />
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com" />
    
    <!-- Preconnect pour ressources externes -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://cdn.tailwindcss.com" />
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/line-awesome/css/line-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/swiper.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <!-- Schema.org / Structured Data -->
    
    
    <!-- Organization Schema -->
    
    
    <!-- BreadcrumbList Schema -->
    @yield('breadcrumb-schema')
    
    <!-- Product Schema (pour pages de prix) -->
    @yield('product-schema')
    
    <!-- Article Schema (pour blog) -->
    @yield('article-schema')
    
    <!-- FAQ Schema -->
    @yield('faq-schema')
    
    <!-- LocalBusiness Schema -->
    
    
    <!-- Styles personnalisés -->
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .bg-orange-custom { background-color: #ff6c00; }
        .text-orange-custom { color: #ff6c00; }
        .border-orange-custom { border-color: #ff6c00; }
        .gradient-bg { background: linear-gradient(135deg, #ff6c00 0%, #e05a00 100%); }
        .btn-primary { background: linear-gradient(135deg, #ff6c00 0%, #e05a00 100%); transition: all 0.3s ease; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(255, 108, 0, 0.3); }
        .btn-outline { border: 1px solid #ff6c00; color: #ff6c00; transition: all 0.3s ease; }
        .btn-outline:hover { background-color: #ff6c00; color: white; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        .hero-section { background: linear-gradient(135deg, #fff5eb 0%, #ffe8d9 100%); }
        .loading { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; }
        .loading.active { display: block; }
        
        /* SEO Optimization */
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0; }
        
        /* Print styles */
        @media print {
            .no-print { display: none !important; }
            body { background: white; color: black; }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-white" x-cloak x-data="customizer" :class="$store.app.isDarkMode?'dark':''">
    
    <div class="loading" id="loading">
        <div class="w-16 h-16 border-4 border-orange-custom border-t-transparent rounded-full animate-spin"></div>
    </div>

    <!-- Navigation -->
    @include('partials.navbarpublic')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footerpublic')

    <!-- Scripts -->
    <script src="{{ asset('assets/js/libs/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/libs/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/libs/alpine.collapse.js') }}"></script>
    <script src="{{ asset('assets/js/libs/alpine.persist.js') }}"></script>
    <script defer src="{{ asset('assets/js/libs/alpine.min.js') }}"></script>
    <script src="{{ asset('assets/js/libs/nice-select2.js') }}"></script>
    <script src="{{ asset('assets/js/charts.js') }}"></script>
    
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(() => console.log('PWA prête 🚀'))
                .catch(err => console.log(err));
        }
        
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
        
        // Smooth scroll
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
    </script>

    
    
    @stack('scripts')
</body>
</html>