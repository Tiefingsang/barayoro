@extends('layouts.master')

@section('title', $job->title . ' - Barayoro')
@section('description', Str::limit($job->description, 160))

@section('content')
<div class="bg-white py-12">
    <div class="container mx-auto px-4 md:px-6 max-w-4xl">
        <nav class="text-sm text-gray-500 mb-6">
            <a href="{{ route('jobs.list') }}" class="hover:text-orange-custom">Offres d'emploi</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-700">{{ $job->title }}</span>
        </nav>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-8">
                <div class="flex justify-between items-start flex-wrap gap-4 mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $job->title }}</h1>
                        <div class="flex flex-wrap gap-3">
                            <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm">{{ strtoupper($job->contract_type) }}</span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-sm">{{ $job->location }}</span>
                            @if($job->is_urgent)
                            <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-sm">Urgent</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Publiée le {{ $job->created_at->format('d/m/Y') }}</div>
                        @if($job->expires_at)
                        <div class="text-sm text-gray-500">Expire le {{ $job->expires_at->format('d/m/Y') }}</div>
                        @endif
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Description du poste</h2>
                    <div class="prose max-w-none">
                        {!! nl2br(e($job->description)) !!}
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Prérequis</h2>
                    <div class="prose max-w-none">
                        {!! nl2br(e($job->requirements)) !!}
                    </div>
                </div>

                @if($job->salary_min || $job->salary_max)
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-euro-sign text-orange-custom"></i>
                        <span class="font-semibold">Salaire :</span>
                        <span>{{ number_format($job->salary_min ?? 0) }} - {{ number_format($job->salary_max ?? 0) }} €</span>
                        <span class="text-gray-500 text-sm">(selon expérience)</span>
                    </div>
                </div>
                @endif

                <div class="flex gap-4">
                    <a href="{{ route('jobs.apply', $job->id) }}" class="px-8 py-3 gradient-bg text-white rounded-lg btn-primary font-semibold">
                        <i class="fas fa-paper-plane mr-2"></i>Postuler maintenant
                    </a>
                    <a href="{{ route('jobs.list') }}" class="px-8 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>Retour aux offres
                    </a>
                </div>
            </div>
        </div>

        @if($similarJobs->isNotEmpty())
        <div class="mt-12">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Offres similaires</h3>
            <div class="grid md:grid-cols-2 gap-4">
                @foreach($similarJobs as $similar)
                <a href="{{ route('jobs.details', $similar->id) }}" class="block bg-gray-50 rounded-lg p-4 hover:shadow-md transition">
                    <h4 class="font-semibold text-gray-800 hover:text-orange-custom">{{ $similar->title }}</h4>
                    <p class="text-sm text-gray-500">{{ $similar->location }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection