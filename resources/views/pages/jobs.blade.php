{{-- resources/views/pages/jobs.blade.php --}}
@extends('layouts.master')

@section('title', 'Offres d\'emploi - Barayoro')
@section('description', 'Consultez toutes nos offres d\'emploi et rejoignez une équipe dynamique.')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Offres d\'emploi']
    ]" />

    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Offres d'emploi
                </h1>
                <p class="text-xl text-gray-600">
                    Rejoignez une équipe passionnée et participez à l'aventure Barayoro
                </p>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="grid md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Toutes</option>
                            @foreach($companyTypes ?? [] as $type)
                            <option value="{{ $type->slug ?? '' }}">{{ $type->name ?? $type->type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Localisation</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Toutes</option>
                            <option value="bamako">Bamako</option>
                            <option value="dakar">Dakar</option>
                            <option value="abidjan">Abidjan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type de contrat</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Tous</option>
                            <option value="cdi">CDI</option>
                            <option value="cdd">CDD</option>
                            <option value="stage">Stage</option>
                            <option value="freelance">Freelance</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                        <input type="text" placeholder="Titre, mots-clés..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>
            </div>

            <!-- Jobs List -->
            <div class="space-y-4">
                @forelse($jobOffers as $job)
                <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition group">
                    <div class="flex flex-wrap justify-between items-start gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-3 py-1 bg-orange-100 text-orange-custom rounded-full text-sm font-semibold">
                                    {{ $job->type_contrat ?? 'CDI' }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $job->location ?? 'Bamako, Mali' }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>Publié le {{ $job->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-orange-custom transition">
                                <a href="{{ route('jobs.details', $job->id) }}">{{ $job->title }}</a>
                            </h3>
                            <p class="text-gray-600 mb-3">{{ Str::limit($job->description ?? '', 150) }}</p>
                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                <span><i class="fas fa-building mr-1"></i>{{ $job->company->name ?? 'Barayoro' }}</span>
                                <span><i class="fas fa-graduation-cap mr-1"></i>{{ $job->experience_level ?? 'Débutant accepté' }}</span>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('jobs.details', $job->id) }}" class="inline-flex items-center px-6 py-2 bg-orange-custom text-white rounded-lg hover:bg-orange-700 transition">
                                Postuler
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 bg-white rounded-2xl">
                    <i class="fas fa-briefcase text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Aucune offre d'emploi disponible pour le moment.</p>
                </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $jobOffers->links() }}
            </div>
        </div>
    </section>
@endsection