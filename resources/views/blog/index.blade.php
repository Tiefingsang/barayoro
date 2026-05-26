@extends('layouts.app')

@section('title', 'Gestion du Blog')

@section('content')
<div class="bg-white py-8 rounded-xl shadow-sm">
    <div class="container mx-auto px-4 md:px-6">
        
        <!-- En-tête de la page -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Gestion du Blog</h1>
                <p class="text-sm text-gray-500 mt-1">Administrez, modifiez et suivez les performances de vos articles.</p>
            </div>
            <a href="{{ route('blog.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-300 text-white font-medium text-sm rounded-lg hover:bg-opacity-90 transition duration-300">
                <i class="las la-plus text-lg"></i>
                Ajouter un article
            </a>
        </div>

        <!-- Alertes de succès/erreur -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="las la-check-circle text-xl"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                    <i class="las la-times"></i>
                </button>
            </div>
        @endif

        <!-- Tableau des articles -->
        <div class="w-full overflow-x-auto border border-gray-100 rounded-xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs font-semibold uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4">Image & Titre</th>
                        <th class="px-6 py-4">Catégorie</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4">Vues</th>
                        <th class="px-6 py-4">Date de publication</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($posts as $post)
                        <tr class="hover:bg-gray-50/50 transition">
                            <!-- Image & Titre -->
                            <td class="px-6 py-4 max-w-md">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-10 rounded-lg overflow-hidden bg-gray-100 shrink-0 border border-gray-200">
                                        @if($post->featured_image)
                                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                                <i class="las la-image text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="truncate">
                                        <span class="font-medium text-gray-900 block truncate" title="{{ $post->title }}">
                                            {{ $post->title }}
                                        </span>
                                        <span class="text-xs text-gray-400 block">Par {{ $post->author->name ?? 'Inconnu' }}</span>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Catégorie -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                                    {{ $post->category->name ?? 'Sans catégorie' }}
                                </span>
                            </td>

                            <!-- Statut -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($post->status === 'published')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-green-50 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                        Publié
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-amber-50 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Brouillon
                                    </span>
                                @endif
                            </td>

                            <!-- Vues -->
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-600">
                                <i class="las la-eye text-base mr-1 align-middle"></i>{{ $post->views ?? 0 }}
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 text-xs">
                                {{ $post->published_at ? $post->published_at->format('d/m/Y à H:i') : '---' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Voir l'article en ligne -->
                                    <a href="{{ route('blog.details', $post->slug) }}" target="_blank" class="p-1.5 text-gray-500 hover:text-primary-300 transition rounded-md hover:bg-gray-100" title="Voir l'article">
                                        <i class="las la-external-link-alt text-lg"></i>
                                    </a>
                                    
                                    <!-- Modifier -->
                                    <a href="{{ route('blog.edit', $post->id) }}" class="p-1.5 text-blue-600 hover:text-blue-800 transition rounded-md hover:bg-blue-50" title="Modifier">
                                        <i class="las la-edit text-lg"></i>
                                    </a>

                                    <!-- Supprimer -->
                                    <form action="{{ route('blog.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-600 hover:text-red-800 transition rounded-md hover:bg-red-50" title="Supprimer">
                                            <i class="las la-trash-alt text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="las la-folder-open text-4xl"></i>
                                    <span>Aucun article trouvé pour le moment.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        @endif

    </div>
</div>
@endsection