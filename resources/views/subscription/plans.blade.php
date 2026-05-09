@extends('layouts.app')

@section('title', 'Plans d\'abonnement | Barayoro')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-12">
    <div class="container mx-auto px-4">
        
        <!-- En-tête -->
        <div class="text-center mb-12">
            <div class="inline-block px-4 py-1 bg-orange-100 text-orange-600 rounded-full text-sm font-semibold mb-4">
                <i class="las la-gem mr-1"></i> Offre limitée
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Choisissez votre plan</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Période d'essai gratuite de 30 jours, puis abonnement annuel</p>
        </div>

        @php
            $company = Auth::user()->company;
        @endphp

        <!-- Message période d'essai -->
        @if($company && $company->isOnTrial())
        <div class="max-w-2xl mx-auto mb-8">
            <div class="bg-gradient-to-r from-orange-50 to-orange-100 border-l-4 border-orange-500 rounded-lg p-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="las la-hourglass-half text-orange-500 text-xl"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-orange-800">📅 Vous êtes actuellement en période d'essai.</p>
                        <p class="text-orange-700 font-semibold">Il vous reste <strong>{{ $company->getTrialDaysRemaining() }} jours</strong> d'essai gratuit.</p>
                    </div>
                    <div class="ml-auto">
                        <a href="#premium" class="px-4 py-2 bg-orange-500 text-white rounded-lg text-sm font-semibold hover:bg-orange-600 transition">Souscrire</a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Plans -->
        <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            
            <!-- Plan Essai Gratuit -->
            <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gray-300"></div>
                
                <div class="p-8">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <i class="las la-gem text-4xl text-gray-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Essai gratuit</h3>
                        <p class="text-gray-500 mt-1">Découvrez Barayoro</p>
                    </div>
                    
                    <div class="text-center mb-6">
                        <span class="text-5xl font-bold text-gray-800">0</span>
                        <span class="text-xl">FCFA</span>
                        <span class="text-gray-400">/30 jours</span>
                    </div>
                    
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-gray-600">
                            <i class="las la-check-circle text-gray-400 text-xl mr-3"></i>
                            <span>5 utilisateurs maximum</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="las la-check-circle text-gray-400 text-xl mr-3"></i>
                            <span>Toutes les fonctionnalités</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="las la-check-circle text-gray-400 text-xl mr-3"></i>
                            <span>Support par email</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="las la-check-circle text-gray-400 text-xl mr-3"></i>
                            <span>30 jours d'essai</span>
                        </li>
                    </ul>
                    
                    <a href="{{ route('register') }}" class="block w-full text-center py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 hover:border-gray-400 transition">
                        Commencer l'essai
                    </a>
                </div>
            </div>

            <!-- Plan Premium Annuel (mis en avant) -->
            <div id="premium" class="group relative bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden ring-2 ring-orange-500">
                <!-- Badge populaire -->
                <div class="absolute top-0 right-0">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-4 py-1 rounded-bl-2xl text-sm font-semibold flex items-center gap-1">
                        <i class="las la-fire text-sm"></i>
                        <span>Populaire</span>
                    </div>
                </div>
                
                <!-- Décoration -->
                <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-orange-500 to-orange-600"></div>
                
                <div class="p-8">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform shadow-lg">
                            <i class="las la-crown text-4xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Premium Annuel</h3>
                        <p class="text-orange-500 font-medium mt-1">Pour les entreprises en croissance</p>
                    </div>
                    
                    <div class="text-center mb-6">
                        <span class="text-5xl font-bold text-gray-800">49 000</span>
                        <span class="text-xl">FCFA</span>
                        <span class="text-gray-400">/an</span>
                        <div class="text-sm text-gray-400 mt-1">
                            <i class="las la-tag"></i> Économisez 30% par rapport au mensuel
                        </div>
                    </div>
                    
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-gray-700">
                            <i class="las la-check-circle text-orange-500 text-xl mr-3"></i>
                            <span class="font-medium">Utilisateurs illimités</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="las la-check-circle text-orange-500 text-xl mr-3"></i>
                            <span>Gestion complète des tâches et projets</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="las la-check-circle text-orange-500 text-xl mr-3"></i>
                            <span>Facturation et devis</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="las la-check-circle text-orange-500 text-xl mr-3"></i>
                            <span>Support prioritaire 24/7</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="las la-check-circle text-orange-500 text-xl mr-3"></i>
                            <span>API dédiée et webhooks</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="las la-check-circle text-orange-500 text-xl mr-3"></i>
                            <span>Formation incluse</span>
                        </li>
                    </ul>
                    
                    <!-- Bouton principal -->
                    <a href="{{ route('subscription.checkout') }}" 
                       class="block w-full text-center py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl font-bold transition-all duration-300 hover:shadow-lg hover:scale-[1.02]">
                        S'abonner maintenant
                    </a>
                    
                    <!-- Options de paiement alternatives -->
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <p class="text-center text-xs text-gray-400 mb-3">Paiement sécurisé par</p>
                        <div class="flex justify-center gap-4">
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <i class="fab fa-cc-visa text-xl text-blue-600"></i>
                                <i class="fab fa-cc-mastercard text-xl text-red-500"></i>
                                <i class="fab fa-cc-amex text-xl text-blue-400"></i>
                            </div>
                            <div class="w-px h-5 bg-gray-200"></div>
                            <div class="flex items-center gap-1">
                                <i class="fab fa-orange text-orange-500"></i>
                                <span class="text-sm text-gray-500">Orange Money</span>
                            </div>
                            <div class="w-px h-5 bg-gray-200"></div>
                            <div class="flex items-center gap-1">
                                <i class="fab fa-wave text-teal-500"></i>
                                <span class="text-sm text-gray-500">Wave</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Garanties -->
        <div class="max-w-3xl mx-auto mt-16 text-center">
            <div class="flex flex-wrap justify-center gap-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="las la-check-circle text-green-500 text-xl"></i>
                    </div>
                    <div class="text-left">
                        <p class="font-semibold text-gray-800">Satisfait ou remboursé</p>
                        <p class="text-xs text-gray-500">Sous 14 jours</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="las la-lock text-blue-500 text-xl"></i>
                    </div>
                    <div class="text-left">
                        <p class="font-semibold text-gray-800">Paiement sécurisé</p>
                        <p class="text-xs text-gray-500">SSL & 3D Secure</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="las la-headset text-purple-500 text-xl"></i>
                    </div>
                    <div class="text-left">
                        <p class="font-semibold text-gray-800">Support 24/7</p>
                        <p class="text-xs text-gray-500">Assistance dédiée</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="las la-sync-alt text-orange-500 text-xl"></i>
                    </div>
                    <div class="text-left">
                        <p class="font-semibold text-gray-800">Mises à jour gratuites</p>
                        <p class="text-xs text-gray-500">Nouveautés incluses</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FAQ rapide -->
        <div class="max-w-3xl mx-auto mt-16 text-center">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Questions fréquentes</h3>
            <div class="grid md:grid-cols-2 gap-4 text-left">
                <div class="bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="las la-question-circle text-orange-500 text-xl"></i>
                        <h4 class="font-semibold text-gray-800">Puis-je changer de plan ?</h4>
                    </div>
                    <p class="text-sm text-gray-500">Oui, vous pouvez passer du plan Essai au plan Premium à tout moment.</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="las la-question-circle text-orange-500 text-xl"></i>
                        <h4 class="font-semibold text-gray-800">Que se passe-t-il après l'essai ?</h4>
                    </div>
                    <p class="text-sm text-gray-500">Vos données sont conservées. Vous pouvez continuer avec Premium.</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="las la-question-circle text-orange-500 text-xl"></i>
                        <h4 class="font-semibold text-gray-800">Puis-je être remboursé ?</h4>
                    </div>
                    <p class="text-sm text-gray-500">Oui, sous 14 jours après l'achat, contactez notre support.</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="las la-question-circle text-orange-500 text-xl"></i>
                        <h4 class="font-semibold text-gray-800">Comment payer ?</h4>
                    </div>
                    <p class="text-sm text-gray-500">Carte bancaire, Orange Money, Wave, ou virement bancaire.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .gradient-bg {
        background: linear-gradient(135deg, #ff6c00 0%, #e05a00 100%);
    }
</style>

<script>
function showWavePayment() {
    alert('🚧 Paiement par Wave - Bientôt disponible !');
}
</script>
@endsection