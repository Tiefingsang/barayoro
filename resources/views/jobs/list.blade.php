@extends('layouts.master')

@section('title', 'Offres d\'emploi - Barayoro')
@section('description', 'Découvrez nos offres d\'emploi et rejoignez notre équipe')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Offres d'emploi</h1>
            <p class="text-xl text-gray-600">Rejoignez une équipe passionnée et participez à notre aventure</p>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <form method="GET" action="{{ route('jobs.list') }}" class="grid md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Titre, description..." class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de contrat</label>
                    <select name="contract_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Tous</option>
                        <option value="cdi" {{ request('contract_type') == 'cdi' ? 'selected' : '' }}>CDI</option>
                        <option value="cdd" {{ request('contract_type') == 'cdd' ? 'selected' : '' }}>CDD</option>
                        <option value="stage" {{ request('contract_type') == 'stage' ? 'selected' : '' }}>Stage</option>
                        <option value="alternance" {{ request('contract_type') == 'alternance' ? 'selected' : '' }}>Alternance</option>
                        <option value="freelance" {{ request('contract_type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Localisation</label>
                    <input type="text" name="location" value="{{ request('location') }}" placeholder="Ville, pays..." class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-6 py-2 gradient-bg text-white rounded-lg btn-primary">
                        <i class="fas fa-search mr-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des offres -->
        <div class="space-y-4">
            @forelse($jobs as $job)
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                <div class="flex flex-wrap justify-between items-start gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <h2 class="text-xl font-bold text-gray-800">
                                <a href="{{ route('jobs.details', $job->id) }}" class="hover:text-orange-custom">{{ $job->title }}</a>
                            </h2>
                            <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded-full text-xs">{{ strtoupper($job->contract_type) }}</span>
                            @if($job->is_urgent)
                            <span class="px-2 py-1 bg-red-100 text-red-600 rounded-full text-xs">Urgent</span>
                            @endif
                        </div>
                        <p class="text-gray-600 mb-3">{{ Str::limit($job->description, 200) }}</p>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                            <span><i class="fas fa-building mr-1"></i>{{ $job->company->name ?? 'Barayoro' }}</span>
                            <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $job->location }}</span>
                            <span><i class="fas fa-calendar mr-1"></i>Publiée le {{ $job->created_at->format('d/m/Y') }}</span>
                            @if($job->salary_min || $job->salary_max)
                            <span><i class="fas fa-euro-sign mr-1"></i>{{ number_format($job->salary_min ?? 0) }} - {{ number_format($job->salary_max ?? 0) }} €</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('jobs.details', $job->id) }}" class="px-6 py-2 btn-outline rounded-lg font-semibold whitespace-nowrap">
                        Voir l'offre
                    </a>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <i class="fas fa-briefcase text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Aucune offre d'emploi disponible pour le moment.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $jobs->links() }}
        </div>
    </div>
</div>
@endsection