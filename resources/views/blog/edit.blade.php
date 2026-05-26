@extends('layouts.app')

@section('title', 'Modifier l\'article - ' . $post->title)

@section('content')
<div class="bg-white py-12">
    <div class="container mx-auto px-4 md:px-6 max-w-4xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Modifier l'article</h1>
            <a href="{{ route('blog.list') }}" class="text-gray-600 hover:text-orange-custom">
                <i class="las la-arrow-left mr-1"></i>Retour
            </a>
        </div>

        <form action="{{ route('blog.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                <input type="text" name="title" required value="{{ old('title', $post->title) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-custom focus:border-transparent">
                @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                    <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-custom">
                        <option value="">Sans catégorie</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-custom">
                        <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Brouillon</option>
                        <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Publié</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tags (séparés par des virgules)</label>
                <input type="text" name="tags" value="{{ old('tags', is_array($post->tags) ? implode(', ', $post->tags) : $post->tags) }}" 
                       placeholder="ex: laravel, php, saas"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-custom">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Image à la une</label>
                @if($post->featured_image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Image actuelle" class="w-40 h-24 object-cover rounded-lg border">
                        <p class="text-xs text-gray-500 mt-1">Image actuelle</p>
                    </div>
                @endif
                <input type="file" name="featured_image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-custom">
                @error('featured_image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Résumé</label>
                <textarea name="excerpt" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-custom">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contenu *</label>
                <textarea name="content" id="editor" rows="15" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('content', $post->content) }}</textarea>
                @error('content') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-4">
                <button type="button" onclick="window.history.back()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="px-6 py-2 gradient-bg text-white rounded-lg btn-primary">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection