@extends('layouts.master')

@section('title', $post->title . ' - Barayoro Blog')
@section('description', Str::limit($post->excerpt ?? $post->content, 160))

@section('content')
<div class="bg-white py-12">
    <div class="container mx-auto px-4 md:px-6 max-w-4xl">
        <!-- Fil d'Ariane -->
        <nav class="text-sm text-gray-500 mb-6">
            <a href="{{ route('blog.list') }}" class="hover:text-orange-custom">Blog</a>
            <i class="las la-angle-right mx-2"></i>
            @if($post->category)
            <a href="{{ route('blog.list', ['category' => $post->category_id]) }}" class="hover:text-orange-custom">
                {{ $post->category->name }}
            </a>
            <i class="las la-angle-right mx-2"></i>
            @endif
            <span class="text-gray-700">{{ $post->title }}</span>
        </nav>

        <!-- Article -->
        <article>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">{{ $post->title }}</h1>
            
            <div class="flex flex-wrap items-center gap-4 text-gray-500 mb-6 pb-6 border-b border-gray-200">
                <span><i class="las la-user mr-1"></i>{{ $post->author->name ?? 'Admin' }}</span>
                <span><i class="las la-calendar mr-1"></i>{{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}</span>
                <span><i class="las la-eye mr-1"></i>{{ $post->views }} vues</span>
                @if($post->tags)
                <div class="flex items-center gap-2">
                    <i class="las la-tags"></i>
                    @foreach($post->tags as $tag)
                    <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif
            </div>

            @if($post->featured_image)
            <img src="{{ asset('storage/' . $post->featured_image) }}" 
                 alt="{{ $post->title }}" 
                 class="w-full rounded-2xl shadow-lg mb-8">
            @endif

            <div class="prose prose-lg max-w-none">
                {!! nl2br(e($post->content)) !!}
            </div>
        </article>

        <!-- Partager -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Partager cet article</h3>
            <div class="flex gap-3">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white hover:bg-blue-700 transition">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="w-10 h-10 bg-sky-500 rounded-full flex items-center justify-center text-white hover:bg-sky-600 transition">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($post->title) }}" target="_blank" class="w-10 h-10 bg-blue-700 rounded-full flex items-center justify-center text-white hover:bg-blue-800 transition">
                    <i class="fab fa-linkedin-in"></i>
                </a>
                <a href="mailto:?subject={{ urlencode($post->title) }}&body={{ urlencode(request()->url()) }}" class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center text-white hover:bg-gray-700 transition">
                    <i class="fas fa-envelope"></i>
                </a>
            </div>
        </div>

        <!-- Articles similaires -->
        @if($similarPosts->isNotEmpty())
        <div class="mt-12 pt-8 border-t border-gray-200">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Articles similaires</h3>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($similarPosts as $similar)
                <a href="{{ route('blog.details', $similar->slug) }}" class="block group">
                    <div class="bg-gray-50 rounded-xl p-4 group-hover:shadow-lg transition">
                        @if($similar->featured_image)
                        <img src="{{ asset('storage/' . $similar->featured_image) }}" alt="{{ $similar->title }}" class="w-full h-32 object-cover rounded-lg mb-3">
                        @endif
                        <h4 class="font-semibold text-gray-800 group-hover:text-orange-custom">
                            {{ $similar->title }}
                        </h4>
                        <p class="text-sm text-gray-500 mt-1">{{ $similar->created_at->format('d/m/Y') }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection