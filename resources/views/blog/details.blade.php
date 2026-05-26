{{-- resources/views/pages/blog-details.blade.php --}}
@extends('layouts.master')

@section('title', $post->title . ' - Blog Barayoro')
@section('description', Str::limit($post->excerpt ?? strip_tags($post->content), 160))

@section('content')
    <!-- Fil d'Ariane Dynamique -->
    <x-breadcrumb :items="array_merge(
        [['label' => 'Actualités & Blog', 'url' => route('pages.blog.public')]],
        $post->category ? [['label' => $post->category->name, 'url' => route('pages.blog.public', ['category' => $post->category_id])]] : [],
        [['label' => Str::limit($post->title, 30)]]
    )" />

    <div class="bg-white py-12">
        <div class="container mx-auto px-4 md:px-6 max-w-4xl">
            <!-- Article Principal -->
            <article>
                <!-- Catégorie de l'article -->
                @if($post->category)
                    <span class="inline-block bg-orange-50 text-orange-custom text-xs font-bold px-3 py-1.5 rounded-lg mb-4 uppercase tracking-wider">
                        {{ $post->category->name }}
                    </span>
                @endif

                <!-- Titre H1 -->
                <h1 class="text-3xl md:text-5xl font-black text-gray-900 leading-tight mb-6">
                    {{ $post->title }}
                </h1>
                
                <!-- Métadonnées / Infos de publication -->
                <div class="flex flex-wrap items-center gap-y-3 gap-x-6 text-sm text-gray-500 mb-8 pb-6 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-custom font-bold">
                            {{ strtoupper(substr($post->author->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="font-medium text-gray-700">{{ $post->author->name ?? 'L\'équipe Barayoro' }}</span>
                    </div>
                    
                    <div class="flex items-center gap-1.5">
                        <i class="lar la-calendar text-lg text-gray-400"></i>
                        <span>{{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <i class="las la-eye text-lg text-gray-400"></i>
                        <span>{{ number_format($post->views, 0, ',', ' ') }} vues</span>
                    </div>
                </div>

                <!-- Image à la Une -->
                @if($post->featured_image)
                    <div class="relative rounded-3xl overflow-hidden shadow-xl mb-10 group">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" 
                             alt="{{ $post->title }}" 
                             class="w-full h-[300px] md:h-[450px] object-cover">
                    </div>
                @endif

                <!-- Corps du texte (Typographie Pro) -->
                <div class="prose prose-base md:prose-lg max-w-none text-gray-700 leading-relaxed prose-headings:text-gray-900 prose-headings:font-bold prose-strong:text-gray-900 prose-a:text-orange-custom hover:prose-a:underline">
                    {!! nl2br(e($post->content)) !!}
                </div>

                <!-- Tags / Mots clés -->
                @if($post->tags && count($post->tags) > 0)
                    <div class="mt-10 flex flex-wrap gap-2 items-center">
                        <span class="text-sm font-semibold text-gray-500 mr-1">Tags :</span>
                        @foreach($post->tags as $tag)
                            <span class="text-xs bg-gray-50 text-gray-600 border border-gray-100 px-3 py-1.5 rounded-full font-medium">
                                #{{ trim($tag) }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </article>

            <!-- Section Partage Réseaux Sociaux -->
            <div class="mt-12 p-6 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-gray-800">Cet article vous a été utile ?</h3>
                    <p class="text-sm text-gray-500">Partagez-le avec votre réseau professionnel.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="w-10 h-10 bg-white border border-gray-200 text-[#1877F2] rounded-xl flex items-center justify-center hover:bg-[#1877F2] hover:text-white hover:border-transparent transition shadow-sm">
                        <i class="fab fa-facebook-f text-lg"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="w-10 h-10 bg-white border border-gray-200 text-[#1DA1F2] rounded-xl flex items-center justify-center hover:bg-[#1DA1F2] hover:text-white hover:border-transparent transition shadow-sm">
                        <i class="fab fa-twitter text-lg"></i>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($post->title) }}" target="_blank" class="w-10 h-10 bg-white border border-gray-200 text-[#0A66C2] rounded-xl flex items-center justify-center hover:bg-[#0A66C2] hover:text-white hover:border-transparent transition shadow-sm">
                        <i class="fab fa-linkedin-in text-lg"></i>
                    </a>
                    <a href="mailto:?subject={{ urlencode($post->title) }}&body={{ urlencode(request()->url()) }}" class="w-10 h-10 bg-white border border-gray-200 text-gray-600 rounded-xl flex items-center justify-center hover:bg-gray-800 hover:text-white hover:border-transparent transition shadow-sm">
                        <i class="las la-envelope text-xl"></i>
                    </a>
                </div>
            </div>

            <!-- Section Articles Similaires -->
            @if($similarPosts->isNotEmpty())
                <div class="mt-16 pt-10 border-t border-gray-100">
                    <h3 class="text-2xl font-bold text-gray-900 mb-8 flex items-center gap-2">
                        <span class="w-2 h-6 bg-orange-custom rounded-full"></span>
                        Articles à lire ensuite
                    </h3>
                    
                    <div class="grid sm:grid-cols-3 gap-6">
                        @foreach($similarPosts as $similar)
                            <div class="group flex flex-col h-full bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300">
                                <a href="{{ route('blog.details', $similar->slug) }}" class="block overflow-hidden h-32 w-full bg-gray-50 relative">
                                    @if($similar->featured_image)
                                        <img src="{{ asset('storage/' . $similar->featured_image) }}" 
                                             alt="{{ $similar->title }}" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full gradient-bg flex items-center justify-center text-white font-bold text-sm">
                                            Barayoro
                                        </div>
                                    @endif
                                </a>
                                
                                <div class="p-4 flex flex-col flex-grow">
                                    <h4 class="font-bold text-gray-800 group-hover:text-orange-custom line-clamp-2 transition text-sm mb-2">
                                        <a href="{{ route('blog.details', $similar->slug) }}">
                                            {{ $similar->title }}
                                        </a>
                                    </h4>
                                    <span class="text-[11px] text-gray-400 mt-auto flex items-center gap-1">
                                        <i class="lar la-calendar"></i>
                                        {{ $similar->published_at ? $similar->published_at->format('d M Y') : $similar->created_at->format('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection