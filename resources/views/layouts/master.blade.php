<!DOCTYPE html>
<html dir="ltr" lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon" />
    
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