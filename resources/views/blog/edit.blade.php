@extends('layouts.app')

@section('title', 'Modifier l\'article - Blog')

@section('content')
<div class="bg-white py-12">
    <div class="container mx-auto px-4 md:px-6 max-w-4xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Modifier l'article</h1>
            <a href="{{ route('blog.details', $post->slug) }}" class="text-gray-600 hover:text-orange-custom">
                <i class="las la-eye mr-1"></i>Voir l'article
            </a>
        </div>

        <form action="{{ route('blog.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                <input type="text" name="title" required value="{{ old('title', $post->title) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-custom">
                @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                    <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Sans catégorie</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ ($post->category_id == $category->id) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="draft" {{ $post->status == 'draft' ? 'selected' : '' }}>Brouillon</option>
                        <option value="published" {{ $post->status == 'published' ? 'selected' : '' }}>Publié</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tags (séparés par des virgules)</label>
                <input type="text" name="tags" value="{{ old('tags', implode(', ', $post->tags ?? [])) }}" 
                       placeholder="ex: laravel, php, saas"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>

            @if($post->featured_image)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Image actuelle</label>
                <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-32 h-32 object-cover rounded-lg">
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nouvelle image</label>
                <input type="file" name="featured_image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Résumé</label>
                <textarea name="excerpt" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contenu *</label>
                <textarea name="content" id="editor" rows="15" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('content', $post->content) }}</textarea>
            </div>

            <div class="flex justify-end gap-4">
                <button type="button" onclick="window.history.back()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="px-6 py-2 gradient-bg text-white rounded-lg btn-primary">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection