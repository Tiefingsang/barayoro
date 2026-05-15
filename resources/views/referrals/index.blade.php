@extends('layouts.app')

@section('title', 'Programme de parrainage - Barayoro')
@section('description', 'Parrainez vos amis et gagnez des récompenses')

@section('content')
<div class="bg-white py-12">
    <div class="container mx-auto px-4 md:px-6">
        
        <!-- En-tête -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <div class="inline-flex items-center gap-2 bg-orange-100 text-orange-custom px-4 py-2 rounded-full mb-6">
                <i class="fas fa-gift text-sm"></i>
                <span class="text-sm font-semibold">Parrainage</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                Parrainez vos <span class="text-orange-custom">amis</span>
            </h1>
            <p class="text-xl text-gray-600">
                Invitez vos collègues et amis à rejoindre Barayoro et gagnez des récompenses
            </p>
        </div>

        <!-- Cartes de statistiques -->
        <div class="grid md:grid-cols-4 gap-6 mb-12">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 text-white">
                <i class="fas fa-users text-3xl mb-2"></i>
                <div class="text-3xl font-bold">{{ $stats['total_referrals'] }}</div>
                <div class="text-sm opacity-90">Personnes parrainées</div>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
                <i class="fas fa-check-circle text-3xl mb-2"></i>
                <div class="text-3xl font-bold">{{ $stats['successful_referrals'] }}</div>
                <div class="text-sm opacity-90">Parrainages réussis</div>
            </div>
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-6 text-white">
                <i class="fas fa-clock text-3xl mb-2"></i>
                <div class="text-3xl font-bold">{{ $stats['pending_referrals'] }}</div>
                <div class="text-sm opacity-90">En attente</div>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
                <i class="fas fa-coins text-3xl mb-2"></i>
                <div class="text-3xl font-bold">{{ number_format($stats['total_rewards'], 0, ',', ' ') }} €</div>
                <div class="text-sm opacity-90">Gains totaux</div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Colonne gauche : Code de parrainage -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Votre code de parrainage</h2>
                <p class="text-gray-600 mb-4">
                    Partagez ce code avec vos amis. À chaque inscription, vous gagnez une récompense !
                </p>
                
                <div class="bg-gray-100 rounded-lg p-4 text-center mb-4">
                    <div class="text-3xl font-mono font-bold text-orange-custom tracking-wider">
                        {{ Auth::user()->referral_code }}
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <button onclick="copyReferralCode()" class="flex-1 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                        <i class="fas fa-copy mr-2"></i>Copier le code
                    </button>
                    <button onclick="shareReferral()" class="flex-1 px-4 py-2 gradient-bg text-white rounded-lg btn-primary">
                        <i class="fas fa-share-alt mr-2"></i>Partager
                    </button>
                </div>
                
                <div class="mt-4 p-3 bg-green-50 rounded-lg">
                    <p class="text-sm text-green-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        Pour chaque ami qui s'inscrit avec votre code, vous gagnez <strong>{{ number_format($rewardAmount ?? 5000, 0, ',', ' ') }} Fcfa</strong> de crédit !
                    </p>
                </div>
            </div>

            <!-- Colonne droite : Lien de parrainage -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Votre lien de parrainage</h2>
                <p class="text-gray-600 mb-4">
                    Partagez ce lien sur les réseaux sociaux ou par email.
                </p>
                
                <div class="bg-gray-100 rounded-lg p-3 mb-4">
                    <div class="text-sm text-gray-700 break-all" id="referralLink">
                        {{ $shareUrl }}
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <button onclick="copyReferralLink()" class="flex-1 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                        <i class="fas fa-copy mr-2"></i>Copier le lien
                    </button>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-center">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=Découvrez%20Barayoro%20!&url={{ urlencode($shareUrl) }}" target="_blank" 
                       class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 text-center">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($shareUrl) }}&title=Barayoro" target="_blank" 
                       class="px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 text-center">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Liste des parrainages -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Mes parrainages</h2>
            
            @if($referrals->isNotEmpty())
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Personne parrainée</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Récompense</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($referrals as $referral)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $referral->referred->name ?? 'En attente' }}</div>
                                <div class="text-sm text-gray-500">{{ $referral->referred->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $referral->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                @if($referral->status == 'pending')
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                                @elseif($referral->status == 'successful')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Réussi</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Expiré</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($referral->status == 'successful')
                                    <span class="font-semibold text-green-600">{{ number_format($rewardAmount ?? 5000, 0, ',', ' ') }} €</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($referral->status == 'pending')
                                    <span class="text-gray-400 text-sm">En attente d'inscription</span>
                                @elseif($referral->status == 'successful' && $referral->reward && $referral->reward->status == 'pending')
                                    <button onclick="claimReward({{ $referral->reward->id }})" class="px-3 py-1 bg-green-500 text-white rounded-lg text-sm hover:bg-green-600">
                                        <i class="fas fa-gift mr-1"></i>Réclamer
                                    </button>
                                @elseif($referral->status == 'successful')
                                    <span class="text-green-600 text-sm"><i class="fas fa-check-circle"></i> Récompensé</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="bg-gray-50 rounded-xl p-12 text-center">
                <i class="fas fa-user-friends text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Vous n'avez pas encore de parrainage</p>
                <p class="text-gray-400">Partagez votre code pour inviter vos amis !</p>
            </div>
            @endif
        </div>

        <!-- Récompenses reçues -->
        @if($rewards->isNotEmpty())
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Mes récompenses</h2>
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($rewards as $reward)
                        <tr>
                            <td class="px-6 py-4 text-gray-800">{{ $reward->description ?? 'Récompense de parrainage' }}</td>
                            <td class="px-6 py-4 font-semibold text-green-600">{{ number_format($reward->amount, 0, ',', ' ') }} €</td>
                            <td class="px-6 py-4 text-gray-600">{{ $reward->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                @if($reward->status == 'claimed')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Réclamée</span>
                                @elseif($reward->status == 'pending')
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Expirée</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Section FAQ Parrainage -->
        <div class="mt-12 bg-gray-50 rounded-xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Comment ça marche ?</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-share-alt text-2xl text-white"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">1. Partagez</h3>
                    <p class="text-gray-600 text-sm">Partagez votre code ou lien unique avec vos amis</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-plus text-2xl text-white"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">2. Ils s'inscrivent</h3>
                    <p class="text-gray-600 text-sm">Vos amis s'inscrivent avec votre code de parrainage</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-gift text-2xl text-white"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">3. Gagnez</h3>
                    <p class="text-gray-600 text-sm">Recevez des récompenses pour chaque parrainage réussi</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyReferralCode() {
    const code = "{{ Auth::user()->referral_code }}";
    navigator.clipboard.writeText(code);
    alert('Code copié dans le presse-papier !');
}

function copyReferralLink() {
    const link = document.getElementById('referralLink').innerText;
    navigator.clipboard.writeText(link);
    alert('Lien copié dans le presse-papier !');
}

function shareReferral() {
    const link = "{{ $shareUrl }}";
    if (navigator.share) {
        navigator.share({
            title: 'Barayoro',
            text: 'Découvrez Barayoro, la solution de gestion d\'entreprise !',
            url: link
        });
    } else {
        copyReferralLink();
    }
}

function claimReward(rewardId) {
    if(confirm('Voulez-vous réclamer cette récompense ?')) {
        fetch(`/referrals/rewards/${rewardId}/claim`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(response => response.json())
          .then(data => {
              if(data.success) {
                  location.reload();
              } else {
                  alert('Erreur lors du réclamation');
              }
          });
    }
}
</script>
@endsection