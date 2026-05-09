{{-- resources/views/reviews/manage.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des avis clients')

@section('content')
<div class="bg-white py-12">
    <div class="container mx-auto px-4 md:px-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Gestion des avis clients</h1>
                <p class="text-gray-500 mt-1">Modérez les avis laissés par vos clients</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-orange-custom">
                <i class="fas fa-arrow-left mr-1"></i>Retour
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Statistiques -->
        <div class="grid md:grid-cols-5 gap-4 mb-8">
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                <div class="text-sm text-gray-600">Total</div>
            </div>
            <div class="bg-yellow-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                <div class="text-sm text-gray-600">En attente</div>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</div>
                <div class="text-sm text-gray-600">Approuvés</div>
            </div>
            <div class="bg-red-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                <div class="text-sm text-gray-600">Rejetés</div>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-purple-600">{{ number_format($stats['average_rating'], 1) }}</div>
                <div class="text-sm text-gray-600">Note moyenne</div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-gray-50 rounded-xl p-4 mb-6">
            <form method="GET" action="{{ route('reviews.manage') }}" class="flex flex-wrap gap-4">
                <div>
                    <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvés</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejetés</option>
                    </select>
                </div>
                <div>
                    <select name="rating" class="px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">Toutes les notes</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 étoiles</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 étoiles</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 étoiles</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 étoiles</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 étoile</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                    <i class="fas fa-filter mr-2"></i>Filtrer
                </button>
                <a href="{{ route('reviews.manage') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-redo mr-2"></i>Réinitialiser
                </a>
            </form>
        </div>

        <!-- Liste des avis -->
        <div class="space-y-4">
            @forelse($reviews as $review)
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <!-- En-tête avec note et statut -->
                        <div class="flex items-center gap-3 mb-2 flex-wrap">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-sm text-gray-500">
                                <i class="far fa-calendar-alt mr-1"></i>{{ $review->created_at->format('d/m/Y H:i') }}
                            </span>
                            @if($review->status == 'pending')
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>En attente
                                </span>
                            @elseif($review->status == 'approved')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Approuvé
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Rejeté
                                </span>
                            @endif
                        </div>
                        
                        <!-- Titre -->
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $review->title }}</h3>
                        
                        <!-- Contenu -->
                        <p class="text-gray-600 mb-3">{{ $review->content }}</p>
                        
                        <!-- Informations client -->
                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <span>
                                <i class="fas fa-user mr-1"></i>
                                {{ $review->user->name ?? 'Client' }}
                            </span>
                            <span>
                                <i class="fas fa-envelope mr-1"></i>
                                {{ $review->user->email ?? 'Email non disponible' }}
                            </span>
                        </div>
                        
                        <!-- Raison du rejet si applicable -->
                        @if($review->status == 'rejected' && $review->rejection_reason)
                            <div class="mt-3 p-3 bg-red-50 rounded-lg">
                                <p class="text-sm text-red-700">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <strong>Raison du rejet :</strong> {{ $review->rejection_reason }}
                                </p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex gap-2 ml-4">
                        @if($review->status == 'pending')
                            <form action="{{ route('reviews.approve', $review->id) }}" method="POST" class="inline">
                                @csrf
                                @method('POST')
                                <button type="submit" class="px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition" title="Approuver">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            
                            <button onclick="showRejectModal({{ $review->id }})" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition" title="Rejeter">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                        
                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer définitivement cet avis ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-gray-50 rounded-xl p-12 text-center">
                <i class="fas fa-star text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Aucun avis trouvé</p>
                <p class="text-gray-400">Aucun avis client pour le moment</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    </div>
</div>

<!-- Modal de rejet -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Rejeter l'avis</h3>
        <p class="text-gray-600 mb-4">Veuillez indiquer la raison du rejet (optionnel) :</p>
        
        <form id="rejectForm" method="POST">
            @csrf
            @method('POST')
            <textarea name="reason" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-custom focus:border-orange-custom" placeholder="Raison du rejet..."></textarea>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    Confirmer le rejet
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(reviewId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = '/reviews/' + reviewId + '/reject';
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

// Fermer le modal en cliquant en dehors
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endsection