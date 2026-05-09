@extends('layouts.app')

@section('title', 'Créer une offre d\'emploi')

@section('content')
<div class="bg-white py-12">
    <div class="container mx-auto px-4 md:px-6 max-w-4xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Créer une offre d'emploi</h1>
            <a href="{{ route('jobs.index') }}" class="text-gray-600 hover:text-orange-custom">
                <i class="fas fa-arrow-left mr-1"></i>Retour
            </a>
        </div>

        <form action="{{ route('jobs.store') }}" method="POST" class="space-y-6 bg-white rounded-xl shadow-md p-8">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                <input type="text" name="title" required value="{{ old('title') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-custom focus:border-orange-custom">
                @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de contrat *</label>
                    <select name="contract_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Sélectionner</option>
                        <option value="cdi" {{ old('contract_type') == 'cdi' ? 'selected' : '' }}>CDI</option>
                        <option value="cdd" {{ old('contract_type') == 'cdd' ? 'selected' : '' }}>CDD</option>
                        <option value="stage" {{ old('contract_type') == 'stage' ? 'selected' : '' }}>Stage</option>
                        <option value="alternance" {{ old('contract_type') == 'alternance' ? 'selected' : '' }}>Alternance</option>
                        <option value="freelance" {{ old('contract_type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Localisation *</label>
                    <input type="text" name="location" required value="{{ old('location') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Salaire minimum (€)</label>
                    <input type="number" name="salary_min" value="{{ old('salary_min') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Salaire maximum (€)</label>
                    <input type="number" name="salary_max" value="{{ old('salary_max') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Niveau d'expérience *</label>
                <select name="experience_level" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">Sélectionner</option>
                    <option value="entry" {{ old('experience_level') == 'entry' ? 'selected' : '' }}>Débutant</option>
                    <option value="intermediate" {{ old('experience_level') == 'intermediate' ? 'selected' : '' }}>Intermédiaire</option>
                    <option value="senior" {{ old('experience_level') == 'senior' ? 'selected' : '' }}>Senior</option>
                    <option value="expert" {{ old('experience_level') == 'expert' ? 'selected' : '' }}>Expert</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" rows="8" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prérequis *</label>
                <textarea name="requirements" rows="8" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('requirements') }}</textarea>
                @error('requirements') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date d'expiration</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div class="flex items-center pt-6">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                           class="w-4 h-4 text-orange-custom rounded">
                    <label class="ml-2 text-sm text-gray-700">Offre active</label>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <button type="button" onclick="window.history.back()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="px-6 py-2 gradient-bg text-white rounded-lg btn-primary">
                    <i class="fas fa-save mr-2"></i>Publier l'offre
                </button>
            </div>
        </form>
    </div>
</div>
@endsection