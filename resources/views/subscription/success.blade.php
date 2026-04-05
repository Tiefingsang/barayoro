@extends('layouts.app')

@section('title', 'Paiement réussi')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-md text-center">
        <div class="text-green-500 mb-4">
            <i class="las la-check-circle text-6xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Paiement réussi !</h1>
        <p class="text-gray-600 mb-6">
            Votre abonnement annuel a été activé avec succès. Vous avez maintenant accès à toutes les fonctionnalités de Barayoro.
        </p>
        <a href="{{ route('dashboard') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
            Accéder au tableau de bord
        </a>
    </div>
</div>
@endsection
