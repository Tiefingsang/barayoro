@extends('layouts.app')

@section('title', 'Centre d\'aide')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

        <!-- Fil d'Ariane -->
        <div class="white-box xxxl:p-6">
          <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Centre d'aide</h2>
            <ul class="flex flex-wrap gap-2 items-center">
              <li>
                <a class="flex items-center gap-2" href="{{ route('dashboard') }}">
                  <i class="las la-home shrink-0"></i>
                  <span>Accueil</span>
                </a>
              </li>
              <li class="text-sm text-neutral-100">•</li>
              <li>
                <a class="flex items-center gap-2 text-primary-300" href="#">
                  <i class="las la-question-circle shrink-0"></i>
                  <span>Centre d'aide</span>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <!-- En-tête -->
        <div class="white-box bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
          <div class="text-center py-8 xxl:py-12">
            <i class="las la-question-circle text-6xl text-primary-300 mb-4"></i>
            <h1 class="text-3xl xxl:text-4xl font-bold text-gray-900 dark:text-white mb-4">Comment pouvons-nous vous aider ?</h1>
            <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Trouvez des réponses à vos questions, des guides et de la documentation pour utiliser Barayoro efficacement.</p>

            <!-- Barre de recherche -->
            <div class="max-w-2xl mx-auto mt-8">
              <div class="relative">
                <i class="las la-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl"></i>
                <input type="text"
                       id="search-help"
                       placeholder="Rechercher une aide..."
                       class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent">
              </div>
            </div>
          </div>
        </div>

        <!-- Catégories d'aide -->
        <div class="white-box">
          <h3 class="text-xl font-bold mb-6">Catégories d'aide</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="#debuter" class="help-category p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-300 hover:shadow-md transition group">
              <i class="las la-rocket text-3xl text-primary-300 mb-3"></i>
              <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Commencer</h4>
              <p class="text-sm text-gray-500">Guide de démarrage rapide</p>
            </a>
            <a href="#gestion-utilisateurs" class="help-category p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-300 hover:shadow-md transition group">
              <i class="las la-users text-3xl text-primary-300 mb-3"></i>
              <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Gestion des utilisateurs</h4>
              <p class="text-sm text-gray-500">Ajouter, modifier, gérer les rôles</p>
            </a>
            <a href="#taches" class="help-category p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-300 hover:shadow-md transition group">
              <i class="las la-tasks text-3xl text-primary-300 mb-3"></i>
              <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Tâches et projets</h4>
              <p class="text-sm text-gray-500">Organiser votre travail</p>
            </a>
            <a href="#facturation" class="help-category p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-300 hover:shadow-md transition group">
              <i class="las la-file-invoice text-3xl text-primary-300 mb-3"></i>
              <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Facturation</h4>
              <p class="text-sm text-gray-500">Gérer les factures et paiements</p>
            </a>
            <a href="#abonnement" class="help-category p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-300 hover:shadow-md transition group">
              <i class="las la-credit-card text-3xl text-primary-300 mb-3"></i>
              <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Abonnement</h4>
              <p class="text-sm text-gray-500">Plans, facturation, renouvellement</p>
            </a>
            <a href="#securite" class="help-category p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-300 hover:shadow-md transition group">
              <i class="las la-shield-alt text-3xl text-primary-300 mb-3"></i>
              <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Sécurité</h4>
              <p class="text-sm text-gray-500">Protégez votre compte</p>
            </a>
            <a href="#mobile" class="help-category p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-300 hover:shadow-md transition group">
              <i class="las la-mobile-alt text-3xl text-primary-300 mb-3"></i>
              <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Application mobile</h4>
              <p class="text-sm text-gray-500">Utiliser Barayoro sur mobile</p>
            </a>
            <a href="#faq" class="help-category p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-300 hover:shadow-md transition group">
              <i class="las la-question-circle text-3xl text-primary-300 mb-3"></i>
              <h4 class="font-semibold text-gray-900 dark:text-white mb-1">FAQ</h4>
              <p class="text-sm text-gray-500">Questions fréquentes</p>
            </a>
          </div>
        </div>

        <!-- FAQ Section -->
        <div id="faq" class="white-box">
          <h3 class="text-xl font-bold mb-6">Questions fréquentes</h3>
          <div class="space-y-4">
            <div x-data="{ open: false }" class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
              <button @click="open = !open" class="w-full flex justify-between items-center p-4 text-left hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <span class="font-medium text-gray-900 dark:text-white">Comment créer un compte utilisateur ?</span>
                <i class="las" :class="open ? 'la-minus' : 'la-plus'"></i>
              </button>
              <div x-show="open" x-collapse class="p-4 pt-0 text-gray-600 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700">
                Pour créer un compte utilisateur, allez dans "Utilisateurs" puis cliquez sur "Nouvel utilisateur". Remplissez les informations et choisissez un rôle. L'utilisateur recevra un email avec ses identifiants.
              </div>
            </div>

            <div x-data="{ open: false }" class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
              <button @click="open = !open" class="w-full flex justify-between items-center p-4 text-left hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <span class="font-medium text-gray-900 dark:text-white">Quels sont les différents rôles disponibles ?</span>
                <i class="las" :class="open ? 'la-minus' : 'la-plus'"></i>
              </button>
              <div x-show="open" x-collapse class="p-4 pt-0 text-gray-600 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700">
                <p><strong>Administrateur</strong> : Tous les droits, gestion complète de l'entreprise.</p>
                <p><strong>Gestionnaire</strong> : Peut gérer les utilisateurs, projets, tâches et factures.</p>
                <p><strong>Employé</strong> : Accès limité à ses propres tâches et projets.</p>
              </div>
            </div>

            <div x-data="{ open: false }" class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
              <button @click="open = !open" class="w-full flex justify-between items-center p-4 text-left hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <span class="font-medium text-gray-900 dark:text-white">Comment fonctionne l'abonnement ?</span>
                <i class="las" :class="open ? 'la-minus' : 'la-plus'"></i>
              </button>
              <div x-show="open" x-collapse class="p-4 pt-0 text-gray-600 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700">
                Barayoro propose un abonnement annuel. Vous bénéficiez d'un essai gratuit de 30 jours, puis vous pouvez souscrire à l'offre Premium à 490€/an. Le paiement est sécurisé et une facture vous est envoyée.
              </div>
            </div>

            <div x-data="{ open: false }" class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
              <button @click="open = !open" class="w-full flex justify-between items-center p-4 text-left hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <span class="font-medium text-gray-900 dark:text-white">Comment ajouter des tâches à un projet ?</span>
                <i class="las" :class="open ? 'la-minus' : 'la-plus'"></i>
              </button>
              <div x-show="open" x-collapse class="p-4 pt-0 text-gray-600 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700">
                Allez dans le projet concerné, cliquez sur "Ajouter une tâche". Remplissez le titre, la description, assignez un utilisateur et définissez une date d'échéance. Les tâches peuvent être suivies en temps réel.
              </div>
            </div>

            <div x-data="{ open: false }" class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
              <button @click="open = !open" class="w-full flex justify-between items-center p-4 text-left hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <span class="font-medium text-gray-900 dark:text-white">Puis-je utiliser Barayoro hors ligne ?</span>
                <i class="las" :class="open ? 'la-minus' : 'la-plus'"></i>
              </button>
              <div x-show="open" x-collapse class="p-4 pt-0 text-gray-600 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700">
                Oui ! Barayoro est une application PWA (Progressive Web App). Vous pouvez l'installer sur votre appareil et travailler hors ligne. Les modifications sont synchronisées automatiquement dès que vous êtes connecté.
              </div>
            </div>

            <div x-data="{ open: false }" class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
              <button @click="open = !open" class="w-full flex justify-between items-center p-4 text-left hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <span class="font-medium text-gray-900 dark:text-white">Comment exporter des rapports ?</span>
                <i class="las" :class="open ? 'la-minus' : 'la-plus'"></i>
              </button>
              <div x-show="open" x-collapse class="p-4 pt-0 text-gray-600 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700">
                Dans la section "Rapports", vous pouvez choisir la période et le type de rapport souhaité. Cliquez sur "Exporter" et choisissez le format PDF ou CSV.
              </div>
            </div>

            <div x-data="{ open: false }" class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
              <button @click="open = !open" class="w-full flex justify-between items-center p-4 text-left hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <span class="font-medium text-gray-900 dark:text-white">Comment réinitialiser mon mot de passe ?</span>
                <i class="las" :class="open ? 'la-minus' : 'la-plus'"></i>
              </button>
              <div x-show="open" x-collapse class="p-4 pt-0 text-gray-600 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700">
                Sur la page de connexion, cliquez sur "Mot de passe oublié". Entrez votre email et vous recevrez un lien pour réinitialiser votre mot de passe.
              </div>
            </div>

            <div x-data="{ open: false }" class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
              <button @click="open = !open" class="w-full flex justify-between items-center p-4 text-left hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <span class="font-medium text-gray-900 dark:text-white">Comment contacter le support ?</span>
                <i class="las" :class="open ? 'la-minus' : 'la-plus'"></i>
              </button>
              <div x-show="open" x-collapse class="p-4 pt-0 text-gray-600 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700">
                Vous pouvez nous contacter par email à <a href="mailto:support@barayoro.com" class="text-primary-300">support@barayoro.com</a> ou via le formulaire de contact. Notre équipe vous répondra dans les 24 heures.
              </div>
            </div>
          </div>
        </div>

        <!-- Guides et tutoriels -->
        <div class="white-box">
          <h3 class="text-xl font-bold mb-6">Guides et tutoriels</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="#" class="group flex items-start gap-4 p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-300 hover:shadow-md transition">
              <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-300 group-hover:bg-primary-300 group-hover:text-white transition">
                <i class="las la-file-pdf text-2xl"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Guide de démarrage</h4>
                <p class="text-sm text-gray-500">Téléchargez le guide PDF pour bien démarrer avec Barayoro</p>
              </div>
            </a>
            <a href="#" class="group flex items-start gap-4 p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-300 hover:shadow-md transition">
              <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-300 group-hover:bg-primary-300 group-hover:text-white transition">
                <i class="las la-play-circle text-2xl"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Vidéos tutorielles</h4>
                <p class="text-sm text-gray-500">Regardez nos tutoriels vidéo pour maîtriser Barayoro</p>
              </div>
            </a>
            <a href="#" class="group flex items-start gap-4 p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-300 hover:shadow-md transition">
              <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-300 group-hover:bg-primary-300 group-hover:text-white transition">
                <i class="las la-code text-2xl"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900 dark:text-white mb-1">API Documentation</h4>
                <p class="text-sm text-gray-500">Documentation technique pour les développeurs</p>
              </div>
            </a>
            <a href="#" class="group flex items-start gap-4 p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-300 hover:shadow-md transition">
              <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-300 group-hover:bg-primary-300 group-hover:text-white transition">
                <i class="las la-chalkboard text-2xl"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Webinaires</h4>
                <p class="text-sm text-gray-500">Inscrivez-vous à nos webinaires de formation</p>
              </div>
            </a>
          </div>
        </div>

        <!-- Contact support -->
        <div class="white-box bg-gradient-to-r from-primary-50 to-indigo-50 dark:from-primary-900/20 dark:to-indigo-900/20">
          <div class="text-center py-8">
            <i class="las la-headset text-5xl text-primary-300 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Vous n'avez pas trouvé de réponse ?</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Notre équipe de support est là pour vous aider 24h/24, 7j/7</p>
            <div class="flex flex-wrap gap-4 justify-center">
              <a href="mailto:support@barayoro.com" class="px-6 py-3 bg-primary-300 text-white rounded-xl hover:bg-primary-400 transition flex items-center gap-2">
                <i class="las la-envelope"></i>
                Nous contacter
              </a>
              <a href="#" class="px-6 py-3 border border-primary-300 text-primary-300 rounded-xl hover:bg-primary-300 hover:text-white transition flex items-center gap-2">
                <i class="las la-comments"></i>
                Chat en direct
              </a>
            </div>
          </div>
        </div>

      </div>
@endsection

@push('scripts')
<script>
    // Filtre de recherche
    document.getElementById('search-help')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const faqItems = document.querySelectorAll('.border.border-gray-200');

        faqItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Année dynamique dans le footer
    document.getElementById('current-year').textContent = new Date().getFullYear();
</script>
@endpush
