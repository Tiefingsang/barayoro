@extends('layouts.app')

@section('title', 'Paiement - Abonnement | Barayoro')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            
            <!-- En-tête -->
            <div class="text-center mb-8">
                <div class="inline-block w-16 h-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                    <i class="fas fa-credit-card text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Finaliser votre abonnement</h1>
                <p class="text-gray-500">Choisissez votre moyen de paiement sécurisé</p>
            </div>

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-exclamation-circle text-xl"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-sm">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Résumé de la commande -->
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-4 border-b border-orange-200">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-file-invoice text-orange-500"></i>
                        Résumé de la commande
                    </h2>
                </div>
                
                <div class="px-6 py-5 border-b border-gray-100">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-gray-600">Plan annuel Barayoro</span>
                        <span class="font-bold text-gray-800 text-lg">{{ number_format($amount, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-sm text-gray-500">
                        <p class="flex items-center gap-1"><i class="fas fa-check-circle text-green-500 text-sm"></i> Utilisateurs illimités</p>
                        <p class="flex items-center gap-1"><i class="fas fa-check-circle text-green-500 text-sm"></i> Gestion des tâches</p>
                        <p class="flex items-center gap-1"><i class="fas fa-check-circle text-green-500 text-sm"></i> Facturation et devis</p>
                        <p class="flex items-center gap-1"><i class="fas fa-check-circle text-green-500 text-sm"></i> Support prioritaire</p>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-700">Total à payer</span>
                        <span class="text-2xl font-bold text-orange-600">{{ number_format($amount, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>

                <!-- Formulaire de paiement -->
                <form method="POST" action="{{ route('subscription.process') }}" class="p-6" id="paymentForm">
                    @csrf
                    <input type="hidden" name="plan" value="{{ $plan }}">
                    <input type="hidden" name="amount" value="{{ $amount }}">

                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-credit-card text-orange-500"></i>
                        Moyen de paiement
                    </h2>

                    <!-- Options de paiement -->
                    <div x-data="{ 
                        paymentMethod: 'card',
                        mobileNumber: ''
                    }" class="space-y-3">
                        
                        <!-- Carte bancaire -->
                        <div class="border rounded-xl p-4 transition-all duration-200 hover:border-orange-300 hover:shadow-sm cursor-pointer" 
                             :class="paymentMethod === 'card' ? 'border-orange-500 bg-orange-50/30' : 'border-gray-200'">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="card" x-model="paymentMethod" class="mr-3 text-orange-500 focus:ring-orange-500" checked>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                                <i class="fas fa-credit-card text-xl text-white"></i>
                                            </div>
                                            <div>
                                                <span class="font-semibold text-gray-800">Carte bancaire</span>
                                                <p class="text-xs text-gray-500">Visa, Mastercard, American Express</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-1">
                                            <i class="fab fa-cc-visa text-2xl text-blue-600"></i>
                                            <i class="fab fa-cc-mastercard text-2xl text-red-500"></i>
                                            <i class="fab fa-cc-amex text-2xl text-blue-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Formulaire Carte bancaire -->
                            <div x-show="paymentMethod === 'card'" x-cloak class="mt-4 pt-4 border-t border-gray-100 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de carte</label>
                                    <div class="relative">
                                        <i class="fas fa-credit-card absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                        <input type="text" name="card_number" placeholder="1234 5678 9012 3456"
                                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration</label>
                                        <input type="text" name="card_expiry" placeholder="MM/AA"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                        <input type="text" name="card_cvv" placeholder="123"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Orange Money -->
                        <div class="border rounded-xl p-4 transition-all duration-200 hover:border-orange-300 hover:shadow-sm cursor-pointer"
                             :class="paymentMethod === 'orange_money' ? 'border-orange-500 bg-orange-50/30' : 'border-gray-200'">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="orange_money" x-model="paymentMethod" class="mr-3 text-orange-500 focus:ring-orange-500">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center">
                                                <img src="{{ asset('assets/images/payement/orange.webp') }}" alt="Orange Money" class="w-8 h-8 object-contain">
                                            </div>
                                            <div>
                                                <span class="font-semibold text-gray-800">Orange Money</span>
                                                <p class="text-xs text-gray-500">Paiement mobile Orange Money</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <img src="{{ asset('assets/images/payement/orange.webp') }}" alt="Orange Money" class="w-6 h-6 object-contain">
                                            <span class="text-xs text-gray-500">Orange Money</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Formulaire Orange Money -->
                            <div x-show="paymentMethod === 'orange_money'" x-cloak class="mt-4 pt-4 border-t border-gray-100">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Numéro Orange Money</label>
                                    <div class="relative">
                                        <i class="fas fa-phone-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                        <input type="tel" name="mobile_number" x-model="mobileNumber" 
                                               placeholder="77 123 45 67"
                                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                                    </div>
                                    <div class="flex items-center gap-2 mt-2">
                                        <i class="fas fa-info-circle text-gray-400 text-sm"></i>
                                        <p class="text-xs text-gray-500">Vous recevrez une demande de paiement sur votre téléphone Orange Money</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Wave -->
                        <div class="border rounded-xl p-4 transition-all duration-200 hover:border-orange-300 hover:shadow-sm cursor-pointer"
                             :class="paymentMethod === 'wave' ? 'border-orange-500 bg-orange-50/30' : 'border-gray-200'">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="wave" x-model="paymentMethod" class="mr-3 text-orange-500 focus:ring-orange-500">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center">
                                                <img src="{{ asset('assets/images/payement/wave-logo.png') }}" alt="Wave" class="w-8 h-8 object-contain">
                                            </div>
                                            <div>
                                                <span class="font-semibold text-gray-800">Wave</span>
                                                <p class="text-xs text-gray-500">Paiement mobile Wave</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <img src="{{ asset('assets/images/payement/wave-logo.png') }}" alt="Wave" class="w-6 h-6 object-contain">
                                            <span class="text-xs text-gray-500">Wave</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Formulaire Wave -->
                            <div x-show="paymentMethod === 'wave'" x-cloak class="mt-4 pt-4 border-t border-gray-100">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Numéro Wave</label>
                                    <div class="relative">
                                        <i class="fas fa-phone-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                        <input type="tel" name="mobile_number" x-model="mobileNumber" 
                                               placeholder="77 123 45 67"
                                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                                    </div>
                                    <div class="flex items-center gap-2 mt-2">
                                        <i class="fas fa-info-circle text-gray-400 text-sm"></i>
                                        <p class="text-xs text-gray-500">Vous recevrez une demande de paiement sur votre application Wave</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton de paiement -->
                    <button type="submit" 
                            class="w-full mt-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl font-bold transition-all duration-300 hover:shadow-lg hover:scale-[1.02] flex items-center justify-center gap-2">
                        <i class="fas fa-lock"></i>
                        Payer {{ number_format($amount, 0, ',', ' ') }} FCFA
                    </button>

                    <!-- Sécurité -->
                    <div class="mt-6 flex flex-col items-center gap-3">
                        <div class="flex flex-wrap justify-center gap-4">
                            <div class="flex items-center gap-2 text-xs text-gray-400">
                                <i class="fas fa-shield-alt text-green-500"></i>
                                <span>Paiement sécurisé SSL</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-400">
                                <i class="fas fa-lock text-green-500"></i>
                                <span>Données cryptées</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-400">
                                <i class="fas fa-clock text-green-500"></i>
                                <span>Confirmation immédiate</span>
                            </div>
                        </div>
                        <p class="text-center text-xs text-gray-400">
                            Vos informations bancaires sont cryptées et ne sont jamais stockées sur nos serveurs.
                        </p>
                    </div>
                </form>
            </div>
            
            <!-- Lien retour -->
            <div class="text-center mt-6">
                <a href="{{ route('subscription.plans') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-orange-500 transition">
                    <i class="fas fa-arrow-left"></i>
                    Retour aux plans d'abonnement
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

<!-- Script de validation -->
<script>
document.getElementById('paymentForm')?.addEventListener('submit', function(e) {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    const mobileNumber = document.querySelector('input[name="mobile_number"]');
    
    if ((paymentMethod === 'orange_money' || paymentMethod === 'wave') && mobileNumber) {
        if (!mobileNumber.value.trim()) {
            e.preventDefault();
            alert('Veuillez saisir votre numéro de téléphone');
            mobileNumber.focus();
        }
    }
});
</script>
@endsection