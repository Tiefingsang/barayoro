@extends('layouts.app')

@section('title', 'Créer un tour')

@section('content')
<div class="bg-white py-12">
    <div class="container mx-auto px-4 md:px-6 max-w-4xl">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Créer un tour</h1>
                <p class="text-gray-500 mt-1">Ajoutez un nouveau circuit ou excursion</p>
            </div>
            <a href="{{ route('admin.tours.index') }}" class="text-gray-600 hover:text-orange-custom">
                <i class="fas fa-arrow-left mr-1"></i>Retour
            </a>
        </div>

        <form action="{{ route('admin.tours.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white rounded-xl shadow-md p-8">
            @csrf

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                    <input type="text" name="title" required value="{{ old('title') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-custom focus:border-orange-custom">
                    @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                    <select name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Sélectionner</option>
                        <option value="Culturel" {{ old('category') == 'Culturel' ? 'selected' : '' }}>Culturel</option>
                        <option value="Nature" {{ old('category') == 'Nature' ? 'selected' : '' }}>Nature</option>
                        <option value="Aventure" {{ old('category') == 'Aventure' ? 'selected' : '' }}>Aventure</option>
                        <option value="Détente" {{ old('category') == 'Détente' ? 'selected' : '' }}>Détente</option>
                        <option value="Gastronomique" {{ old('category') == 'Gastronomique' ? 'selected' : '' }}>Gastronomique</option>
                    </select>
                    @error('category') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix (€) *</label>
                    <input type="number" name="price" required value="{{ old('price') }}" step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durée (jours) *</label>
                    <input type="number" name="duration_days" required value="{{ old('duration_days', 1) }}" min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('duration_days') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de début *</label>
                    <input type="date" name="start_date" required value="{{ old('start_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('start_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin *</label>
                    <input type="date" name="end_date" required value="{{ old('end_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('end_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Localisation *</label>
                    <input type="text" name="location" required value="{{ old('location') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('location') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre max de participants *</label>
                    <input type="number" name="max_participants" required value="{{ old('max_participants', 10) }}" min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('max_participants') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Image à la une</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <p class="text-gray-500 text-xs mt-1">Formats acceptés : JPG, PNG (max 5MB)</p>
                @error('image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" rows="8" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-orange-custom rounded">
                    <label class="ml-2 text-sm text-gray-700">Actif (visible sur le site)</label>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-gray-200">
                <button type="button" onclick="window.history.back()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="px-6 py-2 gradient-bg text-white rounded-lg btn-primary">
                    <i class="fas fa-save mr-2"></i>Créer le tour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection