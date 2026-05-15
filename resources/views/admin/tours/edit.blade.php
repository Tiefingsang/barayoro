@extends('layouts.app')

@section('title', 'Modifier le tour')

@section('content')
<div class="bg-white py-12">
    <div class="container mx-auto px-4 md:px-6 max-w-4xl">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Modifier le tour</h1>
                <p class="text-gray-500 mt-1">{{ $tour->title }}</p>
            </div>
            <a href="{{ route('admin.tours.index') }}" class="text-gray-600 hover:text-orange-custom">
                <i class="fas fa-arrow-left mr-1"></i>Retour
            </a>
        </div>

        <form action="{{ route('admin.tours.update', $tour->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white rounded-xl shadow-md p-8">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                    <input type="text" name="title" required value="{{ old('title', $tour->title) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                    <select name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="Culturel" {{ $tour->category == 'Culturel' ? 'selected' : '' }}>Culturel</option>
                        <option value="Nature" {{ $tour->category == 'Nature' ? 'selected' : '' }}>Nature</option>
                        <option value="Aventure" {{ $tour->category == 'Aventure' ? 'selected' : '' }}>Aventure</option>
                        <option value="Détente" {{ $tour->category == 'Détente' ? 'selected' : '' }}>Détente</option>
                        <option value="Gastronomique" {{ $tour->category == 'Gastronomique' ? 'selected' : '' }}>Gastronomique</option>
                    </select>
                    @error('category') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix (€) *</label>
                    <input type="number" name="price" required value="{{ old('price', $tour->price) }}" step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durée (jours) *</label>
                    <input type="number" name="duration_days" required value="{{ old('duration_days', $tour->duration_days) }}" min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de début *</label>
                    <input type="date" name="start_date" required value="{{ old('start_date', $tour->start_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin *</label>
                    <input type="date" name="end_date" required value="{{ old('end_date', $tour->end_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Localisation *</label>
                    <input type="text" name="location" required value="{{ old('location', $tour->location) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre max de participants *</label>
                    <input type="number" name="max_participants" required value="{{ old('max_participants', $tour->max_participants) }}" min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>

            @if($tour->image)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Image actuelle</label>
                <div class="flex items-center gap-4">
                    <img src="{{ asset('storage/' . $tour->image) }}" alt="{{ $tour->title }}" class="w-32 h-32 object-cover rounded-lg">
                    <span class="text-sm text-gray-500">Image actuelle</span>
                </div>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nouvelle image</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <p class="text-gray-500 text-xs mt-1">Laissez vide pour conserver l'image actuelle</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" rows="8" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('description', $tour->description) }}</textarea>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ $tour->is_active ? 'checked' : '' }}
                           class="w-4 h-4 text-orange-custom rounded">
                    <label class="ml-2 text-sm text-gray-700">Actif (visible sur le site)</label>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.tours.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 gradient-bg text-white rounded-lg btn-primary">
                    <i class="fas fa-save mr-2"></i>Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection