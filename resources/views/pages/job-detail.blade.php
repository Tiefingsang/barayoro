{{-- resources/views/pages/job-detail.blade.php --}}
@extends('layouts.master')

@section('title', $job->title . ' - Barayoro')
@section('description', $job->description)

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Offres d\'emploi', 'url' => route('jobs.list')],
        ['label' => $job->title]
    ]" />

    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 md:px-6">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-8">
                        <div class="flex flex-wrap justify-between items-start gap-4 mb-6">
                            <div>
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="px-3 py-1 bg-orange-100 text-orange-custom rounded-full text-sm font-semibold">
                                        {{ $job->type_contrat ?? 'CDI' }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $job->location ?? 'Bamako, Mali' }}
                                    </span>
                                </div>
                                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">{{ $job->title }}</h1>
                                <p class="text-gray-600">
                                    <i class="fas fa-building mr-2"></i>{{ $job->company->name ?? 'Barayoro' }}
                                </p>
                            </div>
                            <a href="{{ route('jobs.apply', $job->id) }}" class="px-8 py-3 gradient-bg text-white rounded-lg font-semibold hover:shadow-lg transition">
                                Postuler maintenant
                            </a>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">Description du poste</h2>
                            <div class="prose max-w-none text-gray-600">
                                {!! nl2br(e($job->description)) !!}
                            </div>
                        </div>
                        
                        @if($job->requirements)
                        <div class="border-t border-gray-200 pt-6 mt-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">Prérequis</h2>
                            <div class="prose max-w-none text-gray-600">
                                {!! nl2br(e($job->requirements)) !!}
                            </div>
                        </div>
                        @endif
                        
                        <div class="border-t border-gray-200 pt-6 mt-6">
                            <a href="{{ route('jobs.apply', $job->id) }}" class="inline-block px-8 py-3 gradient-bg text-white rounded-lg font-semibold hover:shadow-lg transition">
                                Postuler maintenant
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection