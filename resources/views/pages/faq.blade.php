@extends('layouts.master')

@section('title', 'FAQ - Questions fréquentes - Barayoro')
@section('description', 'Trouvez les réponses à vos questions sur Barayoro, notre solution SaaS de gestion d\'entreprise.')

@section('content')
<!-- Hero Section -->
<section class="hero-section pt-32 pb-20 md:pt-40 md:pb-28">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center max-w-4xl mx-auto">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 bg-orange-100 text-orange-custom px-4 py-2 rounded-full mb-6">
                <i class="fas fa-question-circle text-sm"></i>
                <span class="text-sm font-semibold">FAQ</span>
            </div>
            
            <!-- Titre -->
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 mb-6">
                Comment pouvons-nous 
                <span class="text-orange-custom">vous aider</span> ?
            </h1>
            
            <!-- Description -->
            <p class="text-xl text-gray-600 mb-8 leading-relaxed max-w-3xl mx-auto">
                Trouvez rapidement les réponses à vos questions les plus fréquentes sur Barayoro. 
                Une question ? Nous avons la réponse.
            </p>
            
            <!-- Barre de recherche -->
            <div class="max-w-2xl mx-auto">
                <div class="relative">
                    <i class="las la-search absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl"></i>
                    <input type="text" id="search-faq" 
                           placeholder="Rechercher une question..." 
                           class="w-full pl-14 pr-5 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-custom focus:border-transparent shadow-lg text-gray-700">
                    <button id="search-btn" class="absolute right-3 top-1/2 transform -translate-y-1/2 px-4 py-2 gradient-bg text-white rounded-lg text-sm font-semibold">
                        <i class="fas fa-search mr-1"></i> Chercher
                    </button>
                </div>
                <p class="text-sm text-gray-500 mt-3">
                    <i class="fas fa-lightbulb text-orange-custom mr-1"></i> 
                    Exemples : "Comment démarrer ?", "Paiement", "Facture"
                </p>
            </div>
            
            <!-- Stats rapides -->
            <div class="flex flex-wrap justify-center gap-6 mt-8 pt-4">
                <div class="flex items-center gap-2">
                    <i class="las la-check-circle text-green-500 text-xl"></i>
                    <span class="text-gray-600">+50 questions fréquentes</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="las la-clock text-orange-custom text-xl"></i>
                    <span class="text-gray-600">Réponse en moins de 24h</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="las la-headset text-blue-500 text-xl"></i>
                    <span class="text-gray-600">Support disponible 7j/7</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section FAQ Accordéon -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4 md:px-6">
        <!-- Catégories -->
        <div class="flex flex-wrap justify-center gap-3 mb-10">
            <button class="category-filter px-5 py-2 rounded-full bg-orange-custom text-white transition" data-category="all">
                Toutes les questions
            </button>
            @foreach($categories as $category)
            <button class="category-filter px-5 py-2 rounded-full bg-white text-gray-700 hover:bg-orange-custom hover:text-white transition shadow-sm" data-category="{{ $category }}">
                {{ ucfirst($category) }}
            </button>
            @endforeach
        </div>

        <!-- Résultat de recherche -->
        <div id="search-result" class="text-center mb-6 hidden">
            <p class="text-gray-600">
                <span id="result-count" class="font-bold text-orange-custom"></span> résultat(s) trouvé(s)
            </p>
        </div>

        <!-- FAQ Accordéon -->
        <div class="max-w-4xl mx-auto space-y-4" id="faq-container">
            @forelse($faqs as $index => $faq)
            <div class="faq-item bg-white rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition" 
                 data-category="{{ $faq->category ?? 'general' }}" 
                 data-question="{{ strtolower($faq->question) }}" 
                 data-answer="{{ strtolower(strip_tags($faq->answer)) }}">
                <button class="faq-question w-full text-left p-6 font-semibold text-gray-800 hover:text-orange-custom transition flex justify-between items-center">
                    <span class="text-lg flex items-center gap-3">
                        <span class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center text-orange-custom text-sm font-bold">{{ $index + 1 }}</span>
                        {{ $faq->question }}
                    </span>
                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                </button>
                <div class="faq-answer hidden px-6 pb-6 text-gray-600 border-t border-gray-100">
                    <div class="pt-4 leading-relaxed">
                        {!! nl2br(e($faq->answer)) !!}
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-white rounded-xl shadow-md">
                <i class="las la-question-circle text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Aucune question fréquente pour le moment.</p>
            </div>
            @endforelse
        </div>

        <!-- Message "Aucun résultat" -->
        <div id="no-results" class="text-center py-12 hidden">
            <div class="bg-white rounded-xl shadow-md p-8 max-w-2xl mx-auto">
                <i class="las la-search text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Aucune question ne correspond à votre recherche.</p>
                <p class="text-gray-400 mt-2">Essayez d'autres mots-clés ou</p>
                <a href="{{ route('contact') }}" class="inline-block mt-4 text-orange-custom hover:underline font-semibold">
                    Contactez-nous directement
                </a>
            </div>
        </div>

        <!-- Pas de réponse ? -->
        <div class="text-center mt-12 pt-8">
            <div class="bg-white rounded-2xl shadow-xl p-8 max-w-3xl mx-auto border border-gray-100">
                <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="las la-headset text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Vous n'avez pas trouvé votre réponse ?</h3>
                <p class="text-gray-600 mb-6">
                    Notre équipe est là pour vous aider. Contactez-nous et nous vous répondrons dans les plus brefs délais.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-6 py-3 gradient-bg text-white rounded-lg btn-primary">
                        <i class="fas fa-envelope"></i>
                        Formulaire de contact
                    </a>
                    <a href="mailto:support@barayoro.com" class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:border-orange-custom hover:text-orange-custom transition">
                        <i class="fas fa-envelope"></i>
                        support@barayoro.com
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Accordéon FAQ
    document.querySelectorAll('.faq-question').forEach(button => {
        button.addEventListener('click', () => {
            const answer = button.nextElementSibling;
            const icon = button.querySelector('i');
            
            // Fermer les autres
            document.querySelectorAll('.faq-answer').forEach(a => {
                if (a !== answer && !a.classList.contains('hidden')) {
                    a.classList.add('hidden');
                    a.previousElementSibling.querySelector('i').classList.remove('rotate-180');
                }
            });
            
            // Ouvrir/fermer celui-ci
            answer.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        });
    });
    
    // Filtrage par catégorie
    document.querySelectorAll('.category-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Style des boutons
            document.querySelectorAll('.category-filter').forEach(b => {
                b.classList.remove('bg-orange-custom', 'text-white');
                b.classList.add('bg-white', 'text-gray-700');
            });
            this.classList.remove('bg-white', 'text-gray-700');
            this.classList.add('bg-orange-custom', 'text-white');
            
            // Filtrage des questions
            let visibleCount = 0;
            document.querySelectorAll('.faq-item').forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Réinitialiser la recherche
            document.getElementById('search-faq').value = '';
            document.getElementById('search-result').classList.add('hidden');
            document.getElementById('no-results').classList.add('hidden');
        });
    });
    
    // Recherche
    function searchFaq() {
        const searchTerm = document.getElementById('search-faq').value.toLowerCase().trim();
        let visibleCount = 0;
        
        document.querySelectorAll('.faq-item').forEach(item => {
            const question = item.dataset.question;
            const answer = item.dataset.answer;
            
            if (searchTerm === '') {
                // Si pas de recherche, afficher selon la catégorie active
                const activeCategory = document.querySelector('.category-filter.bg-orange-custom').dataset.category;
                if (activeCategory === 'all' || item.dataset.category === activeCategory) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
                document.getElementById('search-result').classList.add('hidden');
            } else {
                // Recherche dans la question et la réponse
                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
                document.getElementById('search-result').classList.remove('hidden');
                document.getElementById('result-count').textContent = visibleCount;
            }
        });
        
        // Afficher ou cacher le message "aucun résultat"
        const noResults = document.getElementById('no-results');
        if (visibleCount === 0 && searchTerm !== '') {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    }
    
    // Écouteurs d'événements
    document.getElementById('search-faq').addEventListener('input', searchFaq);
    document.getElementById('search-btn').addEventListener('click', searchFaq);
    
    // Recherche avec la touche Entrée
    document.getElementById('search-faq').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchFaq();
        }
    });
</script>
@endpush