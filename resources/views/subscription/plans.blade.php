@extends('layouts.app')

@section('title', 'Plans d\'abonnement')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Choisissez votre plan</h1>
        <p class="text-xl text-gray-600">Période d'essai gratuite de 30 jours, puis abonnement annuel</p>
    </div>

    @if($company->isOnTrial())
    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-8 text-center">
        <p>Vous êtes actuellement en période d'essai. Il vous reste <strong>{{ $company->getTrialDaysRemaining() }} jours</strong> d'essai gratuit.</p>
        <p>Souscrivez dès maintenant pour continuer à utiliser Barayoro sans interruption.</p>
    </div>
    @endif

    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="bg-blue-600 px-6 py-8 text-white text-center">
                <h2 class="text-2xl font-bold">Plan Annuel</h2>
                <p class="mt-2">Accès illimité à toutes les fonctionnalités</p>
            </div>
            <div class="px-6 py-8">
                <div class="text-center mb-6">
                    <span class="text-5xl font-bold">49 000</span>
                    <span class="text-xl">FCFA</span>
                    <span class="text-gray-500">/an</span>
                </div>

                <ul class="space-y-3 mb-8">
                    <li class="flex items-center">
                        <i class="las la-check-circle text-green-500 text-xl mr-3"></i>
                        <span>Utilisateurs illimités</span>
                    </li>
                    <li class="flex items-center">
                        <i class="las la-check-circle text-green-500 text-xl mr-3"></i>
                        <span>Gestion complète des tâches et projets</span>
                    </li>
                    <li class="flex items-center">
                        <i class="las la-check-circle text-green-500 text-xl mr-3"></i>
                        <span>Facturation et devis</span>
                    </li>
                    <li class="flex items-center">
                        <i class="las la-check-circle text-green-500 text-xl mr-3"></i>
                        <span>Support prioritaire 24/7</span>
                    </li>
                    <li class="flex items-center">
                        <i class="las la-check-circle text-green-500 text-xl mr-3"></i>
                        <span>Mises à jour gratuites</span>
                    </li>
                </ul>

                <a href="{{ route('subscription.checkout') }}" class="block w-full text-center bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
                    S'abonner maintenant
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
