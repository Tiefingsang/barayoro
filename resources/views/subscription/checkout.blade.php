@extends('layouts.app')

@section('title', 'Paiement - Abonnement')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Finaliser votre abonnement</h1>
            <p class="text-gray-600">Choisissez votre moyen de paiement</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Résumé de la commande -->
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h2 class="text-lg font-semibold">Résumé de la commande</h2>
            </div>
            <div class="px-6 py-4 border-b">
                <div class="flex justify-between mb-2">
                    <span>Plan annuel Barayoro</span>
                    <span class="font-semibold">{{ number_format($amount, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="text-sm text-gray-500">
                    <p>✓ Utilisateurs illimités</p>
                    <p>✓ Gestion des tâches et projets</p>
                    <p>✓ Facturation et devis</p>
                    <p>✓ Support prioritaire</p>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-b">
                <div class="flex justify-between font-bold">
                    <span>Total à payer</span>
                    <span class="text-xl text-blue-600">{{ number_format($amount, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            <!-- Formulaire de paiement -->
            <form method="POST" action="{{ route('subscription.process') }}" class="p-6">
                @csrf
                <input type="hidden" name="plan" value="{{ $plan }}">
                <input type="hidden" name="amount" value="{{ $amount }}">

                <h2 class="text-lg font-semibold mb-4">Moyen de paiement</h2>

                <!-- Options de paiement -->
                <div x-data="{ paymentMethod: 'card' }" class="space-y-4">
                    <div class="border rounded-lg p-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="payment_method" value="card" x-model="paymentMethod" class="mr-3" checked>
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <i class="las la-credit-card text-2xl mr-2"></i>
                                    <span class="font-medium">Carte bancaire</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Visa, Mastercard, American Express</p>
                            </div>
                        </label>
                    </div>

                    <div class="border rounded-lg p-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="payment_method" value="orange_money" x-model="paymentMethod" class="mr-3">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <i class="lab la-orange text-2xl mr-2"></i>
                                    <span class="font-medium">Orange Money</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Paiement mobile Orange Money</p>
                            </div>
                        </label>
                    </div>

                    <div class="border rounded-lg p-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="payment_method" value="wave" x-model="paymentMethod" class="mr-3">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <i class="las la-wave text-2xl mr-2"></i>
                                    <span class="font-medium">Wave</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Paiement mobile Wave</p>
                            </div>
                        </label>
                    </div>

                    <!-- Formulaire Carte bancaire -->
                    <div x-show="paymentMethod === 'card'" x-cloak class="space-y-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de carte</label>
                            <input type="text" name="card_number" placeholder="1234 5678 9012 3456"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration</label>
                                <input type="text" name="card_expiry" placeholder="MM/AA"
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                <input type="text" name="card_cvv" placeholder="123"
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire Orange Money -->
                    <div x-show="paymentMethod === 'orange_money'" x-cloak class="mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Numéro Orange Money</label>
                            <input type="tel" name="mobile_number" placeholder="77 123 45 67"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Vous recevrez une demande de paiement sur votre téléphone</p>
                        </div>
                    </div>

                    <!-- Formulaire Wave -->
                    <div x-show="paymentMethod === 'wave'" x-cloak class="mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Numéro Wave</label>
                            <input type="tel" name="mobile_number" placeholder="77 123 45 67"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Vous recevrez une demande de paiement sur votre application Wave</p>
                        </div>
                    </div>
                </div>

                <!-- Bouton de paiement -->
                <button type="submit" class="w-full mt-8 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Payer {{ number_format($amount, 0, ',', ' ') }} FCFA
                </button>

                <p class="text-center text-xs text-gray-500 mt-4">
                    Paiement sécurisé. Vos informations bancaires sont cryptées.
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
