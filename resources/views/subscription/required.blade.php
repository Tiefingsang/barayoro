@extends('layouts.app')

@section('title', 'Abonnement requis')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-md text-center">
        <div class="text-yellow-500 mb-4">
            <i class="las la-exclamation-triangle text-6xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Abonnement requis</h1>
        <p class="text-gray-600 mb-6">
            Vous devez souscrire à un abonnement pour accéder à cette page.
        </p>
        <a href="{{ route('subscription.plans') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
            Voir les offres
        </a>
    </div>
</div>
@endsection
