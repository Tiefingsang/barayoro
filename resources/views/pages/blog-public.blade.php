{{-- resources/views/pages/blog-public.blade.php --}}
@extends('layouts.master')

@section('title', 'Actualités & Conseils - Barayoro')
@section('description', 'Découvrez les articles, guides et conseils de nos experts pour optimiser la gestion de votre entreprise.')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Actualités & Blog']
    ]" />

    <!-- Section En-tête -->
    <section class="py-16 bg-gradient-to-br from-orange-50 to-white">
        <div class="container mx-auto px-4 md:px-6 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                Notre espace <span class="text-orange-custom">Actualités</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Retrouvez les dernières tendances du marché et nos guides pratiques pour piloter votre entreprise.
            </p>
            
            <!-- Barre de recherche -->
            <div class="max-w-md mx-auto mt-8">
                <form action="{{ route('pages.blog.public') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher un article..." 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-custom text-gray-700 bg-white shadow-sm">
                    <button type="submit" class="px-6 py-3 bg-orange-custom text-white rounded-xl font-semibold hover:shadow-lg transition">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Section Grille et Catégories -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4 md:px-6">
            
            <!-- Filtre des catégories -->
            <div class="flex flex-wrap justify-center gap-2 mb-12">
                <a href="{{ route('pages.blog.public') }}" 
                   class="px-4 py-2 rounded-full text-sm font-medium transition {{ !request('category') ? 'bg-orange-custom text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Tous les articles
                </a>
                @foreach($categories as $category)
                <a href="{{ route('pages.blog.public', ['category' => $category->id]) }}" 
                   class="px-4 py-2 rounded-full text-sm font-medium transition {{ request('category') == $category->id ? 'bg-orange-custom text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ $category->name }}
                </a>
                @endforeach
            </div>

            <!-- Grille de cartes d'articles -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @forelse($posts as $post)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 flex flex-col h-full group">
                    
                    <!-- Image de couverture -->
                    <div class="relative overflow-hidden h-48 w-full bg-gray-100">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500" 
                                 alt="{{ $post->title }}">
                        @else
                            <div class="w-full h-full gradient-bg flex items-center justify-center text-white text-2xl font-bold">
                                Barayoro
                            </div>
                        @endif
                        
                        @if($post->category)
                        <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-orange-custom text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">
                            {{ $post->category->name }}
                        </span>
                        @endif
                    </div>

                    <!-- Contenu de l'article -->
                    <div class="p-6 flex flex-col flex-grow">
                        <h2 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-orange-custom transition line-clamp-2">
                            <a href="{{ route('blog.details', $post->slug) }}">
                                {{ $post->title }}
                            </a>
                        </h2>
                        
                        <p class="text-gray-600 text-sm leading-relaxed mb-4 line-clamp-3 flex-grow">
                            {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 130) }}
                        </p>

                        <!-- Pied de la carte -->
                        <div class="pt-4 border-t border-gray-50 flex items-center justify-between text-xs text-gray-500 mt-auto">
                            <div class="flex items-center gap-2">
                                <i class="far fa-calendar text-gray-400"></i>
                                <span>{{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                            </div>
                            
                            <a href="{{ route('blog.details', $post->slug) }}" class="text-sm font-semibold text-orange-custom hover:underline flex items-center gap-1">
                                Lire l'article <i class="las la-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Si aucun résultat -->
                <div class="col-span-full text-center py-16 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="las la-newspaper text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700 mb-1">Aucun article publié</h3>
                    <p class="text-gray-500 max-w-sm mx-auto">Nous n'avons trouvé aucun résultat correspondant à vos critères de recherche pour le moment.</p>
                    @if(request()->has('search') || request()->has('category'))
                        <a href="{{ route('pages.blog.public') }}" class="inline-block mt-4 text-sm font-semibold text-orange-custom hover:underline">
                            Réinitialiser les filtres
                        </a>
                    @endif
                </div>
                @endforelse
            </div>

            <!-- Liens de pagination -->
            <div class="mt-12 max-w-6xl mx-auto">
                {{ $posts->links() }}
            </div>

        </div>
    </section>
@endsection