@extends('layouts.master')

@section('title', 'Postuler - ' . $job->title)

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 md:px-6 max-w-3xl">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-orange-custom px-8 py-6">
                <h1 class="text-2xl font-bold text-white">Postuler pour "{{ $job->title }}"</h1>
                <p class="text-orange-100 mt-1">{{ $job->location }} - {{ strtoupper($job->contract_type) }}</p>
            </div>

            <form action="{{ route('jobs.apply.store', $job->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf

                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom complet *</label>
                        <input type="text" name="full_name" required value="{{ old('full_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-custom focus:border-orange-custom">
                        @error('full_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" required value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Salaire souhaité (€)</label>
                        <input type="number" name="expected_salary" value="{{ old('expected_salary') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de disponibilité</label>
                    <input type="date" name="available_from" value="{{ old('available_from') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lettre de motivation *</label>
                    <textarea name="cover_letter" rows="6" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('cover_letter') }}</textarea>
                    @error('cover_letter') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">CV (PDF, DOC, DOCX) *</label>
                    <input type="file" name="cv" required accept=".pdf,.doc,.docx" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-gray-500 text-xs mt-1">Taille max : 5MB</p>
                    @error('cv') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="px-8 py-3 gradient-bg text-white rounded-lg btn-primary font-semibold">
                        <i class="fas fa-paper-plane mr-2"></i>Envoyer ma candidature
                    </button>
                    <a href="{{ route('jobs.details', $job->id) }}" class="px-8 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection