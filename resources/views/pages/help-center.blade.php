<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre d'aide - Barayoro</title>
    <meta name="description" content="Trouvez des réponses à vos questions, des guides et de la documentation pour utiliser Barayoro efficacement.">
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #f5f7fb;
        }
        
        /* Couleurs Barayoro */
        :root {
            --barayoro-orange: #ff6c00;
            --barayoro-orange-dark: #e05a00;
            --barayoro-orange-light: #fff5eb;
        }
        
        .bg-barayoro { background-color: #ff6c00; }
        .text-barayoro { color: #ff6c00; }
        .border-barayoro { border-color: #ff6c00; }
        .hover\:bg-barayoro:hover { background-color: #ff6c00; }
        .hover\:text-barayoro:hover { color: #ff6c00; }
        
        .gradient-barayoro {
            background: linear-gradient(135deg, #ff6c00 0%, #e05a00 100%);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ff6c00 0%, #e05a00 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(255, 108, 0, 0.3);
        }
        
        .help-category {
            transition: all 0.3s ease;
        }
        .help-category:hover {
            border-color: #ff6c00;
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .faq-item {
            transition: all 0.3s ease;
        }
        .faq-item:hover {
            border-color: #ff6c00;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .searching {
            animation: pulse 1s ease-in-out;
        }
        
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #ff6c00;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #e05a00;
        }
        
        .highlight {
            background-color: rgba(255, 108, 0, 0.2);
            border-radius: 4px;
            padding: 0 2px;
        }
    </style>
</head>
<body class="min-h-screen">

    <div class="max-w-7xl mx-auto px-4 py-6 md:px-6 lg:px-8">
        
        <!-- Fil d'Ariane -->
        <div class="mb-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">Centre d'aide</h1>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <a href="#" class="hover:text-barayoro transition">Accueil</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-barayoro font-medium">Centre d'aide</span>
                </div>
            </div>
        </div>

        <!-- En-tête avec recherche -->
        <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-2xl p-8 md:p-12 mb-8 text-center shadow-sm">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-md mb-6">
                <i class="las la-question-circle text-5xl text-barayoro"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Comment pouvons-nous vous aider ?
            </h1>
            <p class="text-gray-600 max-w-2xl mx-auto mb-8">
                Trouvez des réponses à vos questions, des guides et de la documentation pour utiliser Barayoro efficacement.
            </p>
            
            <!-- Barre de recherche -->
            <div class="max-w-2xl mx-auto">
                <div class="relative">
                    <i class="las la-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl"></i>
                    <input type="text"
                           id="searchInput"
                           placeholder="Rechercher une aide..."
                           class="w-full pl-12 pr-12 py-4 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-barayoro focus:border-transparent text-gray-700">
                    <i class="las la-sliders-h absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl cursor-pointer hover:text-barayoro transition"></i>
                </div>
                <p class="text-xs text-gray-400 mt-2 text-left">
                    <i class="fas fa-info-circle mr-1"></i> Recherchez par mot-clé
                </p>
            </div>
        </div>

        <!-- Catégories d'aide -->
        <div class="bg-white rounded-2xl p-6 md:p-8 mb-8 shadow-sm">
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="las la-folder-open text-barayoro text-2xl"></i>
                Catégories d'aide
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <a href="#debuter" class="help-category p-4 rounded-xl border border-gray-200 hover:border-barayoro group">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center mb-3 group-hover:bg-barayoro transition">
                        <i class="las la-rocket text-2xl text-barayoro group-hover:text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Commencer</h3>
                    <p class="text-xs text-gray-500">Guide de démarrage rapide</p>
                </a>
                <a href="#utilisateurs" class="help-category p-4 rounded-xl border border-gray-200 hover:border-barayoro group">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center mb-3 group-hover:bg-barayoro transition">
                        <i class="las la-users text-2xl text-barayoro group-hover:text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Utilisateurs</h3>
                    <p class="text-xs text-gray-500">Gestion des comptes et rôles</p>
                </a>
                <a href="#taches" class="help-category p-4 rounded-xl border border-gray-200 hover:border-barayoro group">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center mb-3 group-hover:bg-barayoro transition">
                        <i class="las la-tasks text-2xl text-barayoro group-hover:text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Tâches et projets</h3>
                    <p class="text-xs text-gray-500">Organisation du travail</p>
                </a>
                <a href="#facturation" class="help-category p-4 rounded-xl border border-gray-200 hover:border-barayoro group">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center mb-3 group-hover:bg-barayoro transition">
                        <i class="las la-file-invoice text-2xl text-barayoro group-hover:text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Facturation</h3>
                    <p class="text-xs text-gray-500">Factures et devis</p>
                </a>
                <a href="#paiements" class="help-category p-4 rounded-xl border border-gray-200 hover:border-barayoro group">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center mb-3 group-hover:bg-barayoro transition">
                        <i class="las la-credit-card text-2xl text-barayoro group-hover:text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Paiements</h3>
                    <p class="text-xs text-gray-500">Orange Money, Wave</p>
                </a>
                <a href="#abonnement" class="help-category p-4 rounded-xl border border-gray-200 hover:border-barayoro group">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center mb-3 group-hover:bg-barayoro transition">
                        <i class="las la-calendar-check text-2xl text-barayoro group-hover:text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Abonnement</h3>
                    <p class="text-xs text-gray-500">Plans et renouvellement</p>
                </a>
                <a href="#securite" class="help-category p-4 rounded-xl border border-gray-200 hover:border-barayoro group">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center mb-3 group-hover:bg-barayoro transition">
                        <i class="las la-shield-alt text-2xl text-barayoro group-hover:text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Sécurité</h3>
                    <p class="text-xs text-gray-500">Protection des données</p>
                </a>
                <a href="#mobile" class="help-category p-4 rounded-xl border border-gray-200 hover:border-barayoro group">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center mb-3 group-hover:bg-barayoro transition">
                        <i class="las la-mobile-alt text-2xl text-barayoro group-hover:text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Application mobile</h3>
                    <p class="text-xs text-gray-500">Utilisation sur mobile</p>
                </a>
            </div>
        </div>

        <!-- FAQ Section -->
        <div id="faq" class="bg-white rounded-2xl p-6 md:p-8 mb-8 shadow-sm">
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="las la-question-circle text-barayoro text-2xl"></i>
                Questions fréquentes
            </h2>
            <div id="faqList" class="space-y-4">
                
                <!-- FAQ 1 -->
                <div x-data="{ open: false }" class="faq-item border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-5 text-left hover:bg-gray-50 transition">
                        <span class="font-medium text-gray-900">Comment créer un compte utilisateur ?</span>
                        <i class="las text-xl text-barayoro" :class="open ? 'la-minus' : 'la-plus'"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-5 pb-5 text-gray-600 border-t border-gray-100">
                        <p>Pour créer un compte utilisateur :</p>
                        <ol class="list-decimal list-inside mt-2 space-y-1 ml-2">
                            <li>Allez dans le menu <strong>"Utilisateurs"</strong></li>
                            <li>Cliquez sur <strong>"Nouvel utilisateur"</strong></li>
                            <li>Remplissez les informations (nom, email, rôle)</li>
                            <li>L'utilisateur reçoit un email avec ses identifiants</li>
                        </ol>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div x-data="{ open: false }" class="faq-item border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-5 text-left hover:bg-gray-50 transition">
                        <span class="font-medium text-gray-900">Quels sont les différents rôles disponibles ?</span>
                        <i class="las text-xl text-barayoro" :class="open ? 'la-minus' : 'la-plus'"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-5 pb-5 text-gray-600 border-t border-gray-100">
                        <div class="space-y-3">
                            <div>
                                <span class="font-semibold text-barayoro">Administrateur</span>
                                <p class="text-sm">Tous les droits, gestion complète de l'entreprise.</p>
                            </div>
                            <div>
                                <span class="font-semibold text-barayoro">Gestionnaire</span>
                                <p class="text-sm">Peut gérer les utilisateurs, projets, tâches et factures.</p>
                            </div>
                            <div>
                                <span class="font-semibold text-barayoro">Employé</span>
                                <p class="text-sm">Accès limité à ses propres tâches et projets.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div x-data="{ open: false }" class="faq-item border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-5 text-left hover:bg-gray-50 transition">
                        <span class="font-medium text-gray-900">Comment fonctionne l'abonnement Barayoro ?</span>
                        <i class="las text-xl text-barayoro" :class="open ? 'la-minus' : 'la-plus'"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-5 pb-5 text-gray-600 border-t border-gray-100">
                        <ul class="space-y-2">
                            <li><i class="fas fa-check-circle text-green-500 mr-2"></i> <strong>Essai gratuit</strong> : 30 jours pour tester toutes les fonctionnalités</li>
                            <li><i class="fas fa-check-circle text-green-500 mr-2"></i> <strong>Premium annuel</strong> : 49 000 FCFA / an</li>
                            <li><i class="fas fa-check-circle text-green-500 mr-2"></i> <strong>Paiement sécurisé</strong> : Orange Money ou Wave</li>
                            <li><i class="fas fa-check-circle text-green-500 mr-2"></i> <strong>Facture</strong> : Reçue automatiquement après paiement</li>
                        </ul>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div x-data="{ open: false }" class="faq-item border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-5 text-left hover:bg-gray-50 transition">
                        <span class="font-medium text-gray-900">Comment ajouter des tâches à un projet ?</span>
                        <i class="las text-xl text-barayoro" :class="open ? 'la-minus' : 'la-plus'"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-5 pb-5 text-gray-600 border-t border-gray-100">
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Ouvrez le projet concerné</li>
                            <li>Cliquez sur <strong>"Ajouter une tâche"</strong></li>
                            <li>Remplissez le titre, la description</li>
                            <li>Assignez un utilisateur et définissez une date d'échéance</li>
                            <li>Cliquez sur <strong>"Créer"</strong></li>
                        </ol>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div x-data="{ open: false }" class="faq-item border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-5 text-left hover:bg-gray-50 transition">
                        <span class="font-medium text-gray-900">Puis-je utiliser Barayoro hors ligne ?</span>
                        <i class="las text-xl text-barayoro" :class="open ? 'la-minus' : 'la-plus'"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-5 pb-5 text-gray-600 border-t border-gray-100">
                        <p>Oui ! Barayoro est une <strong>PWA (Progressive Web App)</strong>. Vous pouvez :</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Installer l'application sur votre téléphone ou ordinateur</li>
                            <li>Travailler <strong>hors ligne</strong> sur vos tâches</li>
                            <li>Les modifications sont synchronisées automatiquement</li>
                        </ul>
                    </div>
                </div>

                <!-- FAQ 6 -->
                <div x-data="{ open: false }" class="faq-item border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-5 text-left hover:bg-gray-50 transition">
                        <span class="font-medium text-gray-900">Comment payer mon abonnement avec Orange Money ?</span>
                        <i class="las text-xl text-barayoro" :class="open ? 'la-minus' : 'la-plus'"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-5 pb-5 text-gray-600 border-t border-gray-100">
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Allez dans <strong>"Paramètres"</strong> puis <strong>"Abonnement"</strong></li>
                            <li>Choisissez <strong>"Orange Money"</strong> comme moyen de paiement</li>
                            <li>Entrez votre numéro Orange Money (ex: 77123456)</li>
                            <li>Validez la demande de paiement sur votre téléphone</li>
                            <li>Confirmation immédiate et activation de l'abonnement</li>
                        </ol>
                        <p class="text-sm text-gray-500 mt-3">
                            <i class="fas fa-info-circle text-barayoro mr-1"></i> Les numéros Orange Money commencent par 77, 70, 71 ou 79.
                        </p>
                    </div>
                </div>

                <!-- FAQ 7 -->
                <div x-data="{ open: false }" class="faq-item border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-5 text-left hover:bg-gray-50 transition">
                        <span class="font-medium text-gray-900">Comment réinitialiser mon mot de passe ?</span>
                        <i class="las text-xl text-barayoro" :class="open ? 'la-minus' : 'la-plus'"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-5 pb-5 text-gray-600 border-t border-gray-100">
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Sur la page de connexion, cliquez sur <strong>"Mot de passe oublié"</strong></li>
                            <li>Entrez votre adresse email</li>
                            <li>Vous recevrez un lien de réinitialisation</li>
                            <li>Choisissez un nouveau mot de passe sécurisé</li>
                        </ol>
                    </div>
                </div>

                <!-- FAQ 8 -->
                <div x-data="{ open: false }" class="faq-item border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-5 text-left hover:bg-gray-50 transition">
                        <span class="font-medium text-gray-900">Comment exporter des rapports ?</span>
                        <i class="las text-xl text-barayoro" :class="open ? 'la-minus' : 'la-plus'"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-5 pb-5 text-gray-600 border-t border-gray-100">
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Allez dans <strong>"Rapports"</strong></li>
                            <li>Choisissez la période souhaitée</li>
                            <li>Sélectionnez le type de rapport (ventes, tâches, factures)</li>
                            <li>Cliquez sur <strong>"Exporter"</strong></li>
                            <li>Choisissez le format <strong>PDF</strong> ou <strong>CSV</strong></li>
                        </ol>
                    </div>
                </div>

                <!-- FAQ 9 -->
                <div x-data="{ open: false }" class="faq-item border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-5 text-left hover:bg-gray-50 transition">
                        <span class="font-medium text-gray-900">Comment contacter le support ?</span>
                        <i class="las text-xl text-barayoro" :class="open ? 'la-minus' : 'la-plus'"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-5 pb-5 text-gray-600 border-t border-gray-100">
                        <p>Plusieurs moyens de nous contacter :</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Email : <a href="mailto:support@barayoro.com" class="text-barayoro hover:underline">support@barayoro.com</a></li>
                            <li>Téléphone : <strong>+223 92 51 64 05</strong></li>
                            <li>Formulaire de contact sur le site</li>
                            <li>Délai de réponse : moins de 24h</li>
                        </ul>
                    </div>
                </div>

                <!-- FAQ 10 -->
                <div x-data="{ open: false }" class="faq-item border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-5 text-left hover:bg-gray-50 transition">
                        <span class="font-medium text-gray-900">Barayoro est-il sécurisé ?</span>
                        <i class="las text-xl text-barayoro" :class="open ? 'la-minus' : 'la-plus'"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-5 pb-5 text-gray-600 border-t border-gray-100">
                        <ul class="space-y-2">
                            <li><i class="fas fa-check-circle text-green-500 mr-2"></i> <strong>Chiffrement SSL</strong> : Toutes les données sont cryptées</li>
                            <li><i class="fas fa-check-circle text-green-500 mr-2"></i> <strong>Authentification sécurisée</strong> : Mots de passe hachés</li>
                            <li><i class="fas fa-check-circle text-green-500 mr-2"></i> <strong>Rôles et permissions</strong> : Contrôle d'accès granulaire</li>
                            <li><i class="fas fa-check-circle text-green-500 mr-2"></i> <strong>Backups quotidiens</strong> : Sauvegarde automatique</li>
                            <li><i class="fas fa-check-circle text-green-500 mr-2"></i> <strong>Protection anti-DDoS</strong> : Infrastructure sécurisée</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Message quand aucun résultat -->
            <div id="noResults" class="text-center py-8 hidden">
                <i class="las la-search text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">Aucun résultat trouvé pour "<span id="searchTerm" class="font-medium text-barayoro"></span>"</p>
                <p class="text-sm text-gray-400 mt-1">Essayez d'autres mots-clés ou parcourez les catégories ci-dessus.</p>
            </div>
        </div>

        <!-- Guides et tutoriels -->
        <div class="bg-white rounded-2xl p-6 md:p-8 mb-8 shadow-sm">
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="las la-book-open text-barayoro text-2xl"></i>
                Guides et ressources
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="#" class="group flex items-start gap-4 p-4 rounded-xl border border-gray-200 hover:border-barayoro hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center group-hover:bg-barayoro transition shrink-0">
                        <i class="las la-file-pdf text-2xl text-barayoro group-hover:text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-barayoro transition">Guide de démarrage</h3>
                        <p class="text-sm text-gray-500">Téléchargez le guide PDF pour bien démarrer avec Barayoro</p>
                    </div>
                </a>
                <a href="#" class="group flex items-start gap-4 p-4 rounded-xl border border-gray-200 hover:border-barayoro hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center group-hover:bg-barayoro transition shrink-0">
                        <i class="las la-play-circle text-2xl text-barayoro group-hover:text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-barayoro transition">Vidéos tutorielles</h3>
                        <p class="text-sm text-gray-500">Regardez nos tutoriels vidéo pour maîtriser Barayoro</p>
                    </div>
                </a>
                <a href="#" class="group flex items-start gap-4 p-4 rounded-xl border border-gray-200 hover:border-barayoro hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center group-hover:bg-barayoro transition shrink-0">
                        <i class="las la-chalkboard text-2xl text-barayoro group-hover:text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-barayoro transition">Webinaires</h3>
                        <p class="text-sm text-gray-500">Inscrivez-vous à nos webinaires de formation gratuits</p>
                    </div>
                </a>
                <a href="#" class="group flex items-start gap-4 p-4 rounded-xl border border-gray-200 hover:border-barayoro hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center group-hover:bg-barayoro transition shrink-0">
                        <i class="las la-blog text-2xl text-barayoro group-hover:text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-barayoro transition">Blog Barayoro</h3>
                        <p class="text-sm text-gray-500">Astuces et bonnes pratiques pour votre entreprise</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Contact support -->
        <div class="gradient-barayoro rounded-2xl p-8 md:p-10 text-center shadow-lg">
            <i class="las la-headset text-5xl text-white mb-4"></i>
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">Vous n'avez pas trouvé de réponse ?</h2>
            <p class="text-orange-100 mb-6 max-w-lg mx-auto">
                Notre équipe de support est là pour vous aider.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="mailto:support@barayoro.com" class="px-6 py-3 bg-white text-barayoro rounded-xl font-semibold hover:bg-gray-100 transition flex items-center gap-2">
                    <i class="las la-envelope"></i>
                    Nous contacter
                </a>
                <a href="#" class="px-6 py-3 border-2 border-white text-white rounded-xl font-semibold hover:bg-white hover:text-barayoro transition flex items-center gap-2">
                    <i class="las la-comments"></i>
                    Chat en direct
                </a>
            </div>
            <p class="text-orange-100 text-sm mt-6">
                <i class="las la-clock mr-1"></i> Support disponible du lundi au vendredi, 8h - 18h
            </p>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-400 text-sm py-8">
            <p>© <span id="currentYear"></span> Barayoro - Tous droits réservés</p>
            <p class="mt-1">Version 1.0 | Dernière mise à jour : Mai 2026</p>
        </div>
    </div>

    <script>
        // Recherche dynamique
        const searchInput = document.getElementById('searchInput');
        const faqItems = document.querySelectorAll('.faq-item');
        const noResults = document.getElementById('noResults');
        const searchTermSpan = document.getElementById('searchTerm');
        
        function highlightText(text, searchTerm) {
            if (!searchTerm) return text;
            const regex = new RegExp(`(${searchTerm})`, 'gi');
            return text.replace(regex, '<mark class="bg-orange-200 rounded px-1">$1</mark>');
        }
        
        function searchFaq() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let hasResults = false;
            
            if (searchTermSpan) searchTermSpan.textContent = searchTerm;
            
            faqItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                const questionBtn = item.querySelector('button span');
                const answerDiv = item.querySelector('[x-collapse]');
                
                if (searchTerm === '') {
                    item.style.display = '';
                    if (questionBtn) {
                        questionBtn.innerHTML = questionBtn.textContent;
                    }
                    hasResults = true;
                } else if (text.includes(searchTerm)) {
                    item.style.display = '';
                    if (questionBtn && searchTerm) {
                        const originalText = questionBtn.textContent;
                        questionBtn.innerHTML = highlightText(originalText, searchTerm);
                    }
                    hasResults = true;
                } else {
                    item.style.display = 'none';
                }
            });
            
            if (noResults) {
                if (!hasResults && searchTerm !== '') {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            }
        }
        
        if (searchInput) {
            searchInput.addEventListener('input', searchFaq);
        }
        
        // Année dynamique
        document.getElementById('currentYear').textContent = new Date().getFullYear();
        
        // Animation sur la recherche
        searchInput?.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-barayoro', 'rounded-xl');
        });
        searchInput?.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-barayoro', 'rounded-xl');
        });
    </script>
</body>
</html>