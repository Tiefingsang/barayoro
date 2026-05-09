@extends('layouts.app')

@section('title', 'Modifier l\'offre d\'emploi')

@section('content')
<div class="bg-white py-12">
    <div class="container mx-auto px-4 md:px-6 max-w-4xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Modifier l'offre d'emploi</h1>
            <a href="{{ route('jobs.index') }}" class="text-gray-600 hover:text-orange-custom">
                <i class="fas fa-arrow-left mr-1"></i>Retour
            </a>
        </div>

        <form action="{{ route('jobs.update', $job->id) }}" method="POST" class="space-y-6 bg-white rounded-xl shadow-md p-8">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                <input type="text" name="title" required value="{{ old('title', $job->title) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de contrat *</label>
                    <select name="contract_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="cdi" {{ $job->contract_type == 'cdi' ? 'selected' : '' }}>CDI</option>
                        <option value="cdd" {{ $job->contract_type == 'cdd' ? 'selected' : '' }}>CDD</option>
                        <option value="stage" {{ $job->contract_type == 'stage' ? 'selected' : '' }}>Stage</option>
                        <option value="alternance" {{ $job->contract_type == 'alternance' ? 'selected' : '' }}>Alternance</option>
                        <option value="freelance" {{ $job->contract_type == 'freelance' ? 'selected' : '' }}>Freelance</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Localisation *</label>
                    <input type="text" name="location" required value="{{ old('location', $job->location) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Salaire minimum (€)</label>
                    <input type="number" name="salary_min" value="{{ old('salary_min', $job->salary_min) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Salaire maximum (€)</label>
                    <input type="number" name="salary_max" value="{{ old('salary_max', $job->salary_max) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Niveau d'expérience *</label>
                <select name="experience_level" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="entry" {{ $job->experience_level == 'entry' ? 'selected' : '' }}>Débutant</option>
                    <option value="intermediate" {{ $job->experience_level == 'intermediate' ? 'selected' : '' }}>Intermédiaire</option>
                    <option value="senior" {{ $job->experience_level == 'senior' ? 'selected' : '' }}>Senior</option>
                    <option value="expert" {{ $job->experience_level == 'expert' ? 'selected' : '' }}>Expert</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" rows="8" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('description', $job->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prérequis *</label>
                <textarea name="requirements" rows="8" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('requirements', $job->requirements) }}</textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date d'expiration</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at', $job->expires_at ? $job->expires_at->format('Y-m-d') : '') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div class="flex items-center pt-6">
                    <input type="checkbox" name="is_active" value="1" {{ $job->is_active ? 'checked' : '' }} 
                           class="w-4 h-4 text-orange-custom rounded">
                    <label class="ml-2 text-sm text-gray-700">Offre active</label>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <button type="button" onclick="window.history.back()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="px-6 py-2 gradient-bg text-white rounded-lg btn-primary">
                    <i class="fas fa-save mr-2"></i>Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection