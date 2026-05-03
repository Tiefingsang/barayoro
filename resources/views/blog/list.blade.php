@extends('layouts.master')

@section('title', 'Blog - Barayoro')
@section('description', 'Actualités, conseils et ressources pour mieux gérer votre entreprise')

@section('content')


<!-- Hero Section -->
<section class="hero-section pt-32 pb-20 md:pt-40 md:pb-28">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center max-w-4xl mx-auto">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 bg-orange-100 text-orange-custom px-4 py-2 rounded-full mb-6">
                <i class="fas fa-newspaper text-sm"></i>
                <span class="text-sm font-semibold">Notre blog</span>
            </div>
            
            <!-- Titre -->
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 mb-6">
                Actualités et 
                <span class="text-orange-custom">conseils</span>
            </h1>
            
            <!-- Description -->
            <p class="text-xl text-gray-600 mb-8 leading-relaxed max-w-3xl mx-auto">
                Découvrez nos derniers articles, astuces et ressources pour optimiser 
                la gestion de votre entreprise.
            </p>
            
            <!-- Catégories -->
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('blog.list') }}" 
                   class="px-5 py-2 rounded-full {{ !request('category') ? 'bg-orange-custom text-white' : 'bg-white text-gray-700 hover:bg-orange-custom hover:text-white' }} transition shadow-sm">
                    Tous
                </a>
                @foreach($categories as $category)
                <a href="{{ route('blog.list', ['category' => $category->id]) }}" 
                   class="px-5 py-2 rounded-full {{ request('category') == $category->id ? 'bg-orange-custom text-white' : 'bg-white text-gray-700 hover:bg-orange-custom hover:text-white' }} transition shadow-sm">
                    {{ $category->name }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>


<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 md:px-6">
        <!-- En-tête -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Blog</h1>
            <p class="text-xl text-gray-600">
                Actualités, conseils et ressources pour mieux gérer votre entreprise
            </p>
        </div>

        <!-- Filtre par catégorie -->
        <div class="flex flex-wrap justify-center gap-3 mb-10">
            <a href="{{ route('blog.list') }}" 
               class="px-4 py-2 rounded-full {{ !request('category') ? 'bg-orange-custom text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">
                Tous
            </a>
            @foreach($categories as $category)
            <a href="{{ route('blog.list', ['category' => $category->id]) }}" 
               class="px-4 py-2 rounded-full {{ request('category') == $category->id ? 'bg-orange-custom text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">
                {{ $category->name }}
            </a>
            @endforeach
        </div>

        <!-- Liste des articles -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($posts as $post)
            <article class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover">
                @if($post->featured_image)
                <img src="{{ asset('storage/' . $post->featured_image) }}" 
                     alt="{{ $post->title }}" 
                     class="w-full h-48 object-cover">
                @else
                <div class="w-full h-48 gradient-bg flex items-center justify-center">
                    <i class="las la-image text-5xl text-white opacity-50"></i>
                </div>
                @endif
                
                <div class="p-6">
                    <div class="flex items-center gap-3 text-sm text-gray-500 mb-3">
                        <span><i class="las la-calendar mr-1"></i>{{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}</span>
                        @if($post->category)
                        <span class="px-2 py-1 bg-orange-100 text-orange-custom rounded-full text-xs">
                            {{ $post->category->name }}
                        </span>
                        @endif
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2 hover:text-orange-custom transition">
                        <a href="{{ route('blog.details', $post->slug) }}">{{ $post->title }}</a>
                    </h2>
                    <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit($post->excerpt ?? $post->content, 120) }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">
                            <i class="las la-user mr-1"></i>{{ $post->author->name ?? 'Admin' }}
                        </span>
                        <a href="{{ route('blog.details', $post->slug) }}" class="text-orange-custom hover:underline font-semibold">
                            Lire la suite <i class="las la-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-3 text-center py-12">
                <i class="las la-newspaper text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Aucun article trouvé.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection