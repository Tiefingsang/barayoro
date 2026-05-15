<!-- resources/views/partials/navbarpublic.blade.php -->
<nav class="fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-md shadow-sm z-50">
    <div class="container mx-auto px-4 md:px-6 py-4">
        <div class="flex flex-wrap justify-between items-center">
            <!-- Logo -->
           <a href="{{ route('home') }}" class="flex items-center space-x-2">
    <img src="{{ asset('assets/images/barayoro_ligne.jpeg') }}" alt="Barayoro Logo" class="h-10 w-auto">
   {{--  <span class="text-2xl font-bold text-gray-800">Barayoro</span> --}}
</a>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-orange-custom transition duration-300">Accueil</a>
                <a href="{{ route('features') }}" class="text-gray-600 hover:text-orange-custom transition duration-300">Fonctionnalités</a>
                <a href="{{ route('jobs.list') }}" class="text-gray-600 hover:text-orange-custom transition duration-300">Offres d'emploi</a>
                <a href="{{ route('pricing') }}" class="text-gray-600 hover:text-orange-custom transition duration-300">Tarifs</a>
                <a href="{{ route('contact') }}" class="text-gray-600 hover:text-orange-custom transition duration-300">Contact</a>
                <a href="{{ route('blog.list') }}" class="text-gray-600 hover:text-orange-custom transition duration-300">Blog</a>
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
            <a href="{{ route('home') }}" class="block text-gray-600 hover:text-orange-custom transition">Accueil</a>
            <a href="{{ route('features') }}" class="block text-gray-600 hover:text-orange-custom transition">Fonctionnalités</a>
            <a href="{{ route('jobs.list') }}" class="block text-gray-600 hover:text-orange-custom transition">Offres d'emploi</a>
            <a href="{{ route('pricing') }}" class="block text-gray-600 hover:text-orange-custom transition">Tarifs</a>
            <a href="{{ route('contact') }}" class="block text-gray-600 hover:text-orange-custom transition">Contact</a>
            <a href="{{ route('blog.list') }}" class="block text-gray-600 hover:text-orange-custom transition">Blog</a>
            @auth
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 bg-orange-custom text-white rounded-lg text-center">Tableau de bord</a>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-2 text-orange-custom border border-orange-custom rounded-lg text-center">Connexion</a>
                <a href="{{ route('register') }}" class="block px-4 py-2 gradient-bg text-white rounded-lg text-center">Essai gratuit</a>
            @endauth
        </div>
    </div>
</nav>