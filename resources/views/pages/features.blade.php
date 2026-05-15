{{-- resources/views/pages/features.blade.php --}}
@extends('layouts.master')

@section('title', 'Fonctionnalités - Barayoro')
@section('description', 'Découvrez toutes les fonctionnalités de Barayoro pour gérer votre entreprise efficacement.')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Fonctionnalités']
    ]" />

    <section class="py-16 bg-gradient-to-br from-orange-50 to-white">
        <div class="container mx-auto px-4 md:px-6 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 mb-6">
                Toutes les fonctionnalités pour
                <span class="text-orange-custom">réussir</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Découvrez un écosystème complet d'outils conçus pour simplifier et optimiser 
                la gestion de votre entreprise au quotidien.
            </p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($features as $feature)
                <div class="group p-6 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-orange-200">
                    <div class="w-14 h-14 gradient-bg rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition">
                        <i class="{{ $feature->icon ?? 'las la-cogs' }} text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $feature->title }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $feature->description }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-20 gradient-bg">
        <div class="container mx-auto px-4 md:px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Prêt à essayer Barayoro ?
            </h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                Commencez votre essai gratuit de 30 jours. Aucune carte bancaire requise.
            </p>
            <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-white text-orange-custom rounded-xl font-semibold hover:shadow-xl transition transform hover:-translate-y-1">
                Commencer l'essai gratuit
            </a>
        </div>
    </section>
@endsection