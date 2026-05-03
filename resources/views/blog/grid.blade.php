@extends('layouts.master')

@section('title', 'Blog - Barayoro')
@section('description', 'Actualités, conseils et ressources pour mieux gérer votre entreprise')

@section('content')
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
            <a href="{{ route('blog.grid') }}" 
               class="px-4 py-2 rounded-full {{ !request('category') ? 'bg-orange-custom text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">
                Tous
            </a>
            @foreach($categories as $category)
            <a href="{{ route('blog.grid', ['category' => $category->id]) }}" 
               class="px-4 py-2 rounded-full {{ request('category') == $category->id ? 'bg-orange-custom text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">
                {{ $category->name }}
            </a>
            @endforeach
        </div>

        <!-- Vue en grille -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($posts as $post)
            <a href="{{ route('blog.details', $post->slug) }}" class="group">
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" 
                         alt="{{ $post->title }}" 
                         class="w-full h-40 object-cover group-hover:scale-105 transition duration-300">
                    @else
                    <div class="w-full h-40 gradient-bg flex items-center justify-center">
                        <i class="las la-image text-4xl text-white opacity-50"></i>
                    </div>
                    @endif
                    <div class="p-4">
                        <div class="text-xs text-gray-500 mb-2">
                            {{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}
                        </div>
                        <h3 class="font-bold text-gray-800 group-hover:text-orange-custom line-clamp-2">
                            {{ $post->title }}
                        </h3>
                        <p class="text-gray-600 text-sm mt-2 line-clamp-2">
                            {{ Str::limit($post->excerpt ?? $post->content, 80) }}
                        </p>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-12">
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