@extends('layouts.app')

@section('title', 'Abonnement expiré')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-md text-center">
        <div class="text-red-500 mb-4">
            <i class="las la-exclamation-circle text-6xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Votre abonnement a expiré</h1>
        <p class="text-gray-600 mb-6">
            Votre période d'abonnement est terminée. Pour continuer à utiliser Barayoro,
            veuillez renouveler votre abonnement.
        </p>
        <a href="{{ route('subscription.plans') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
            Renouveler l'abonnement
        </a>
    </div>
</div>
@endsection
