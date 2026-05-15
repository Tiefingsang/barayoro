<!DOCTYPE html>
<html dir="ltr" lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Politique de confidentialité - Barayoro</title>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        /* Couleurs Barayoro */
        :root {
            --barayoro-orange: #ff6c00;
            --barayoro-orange-dark: #e05a00;
            --barayoro-orange-light: #fff5eb;
        }
        
        .text-barayoro { color: #ff6c00; }
        .bg-barayoro { background-color: #ff6c00; }
        .border-barayoro { border-color: #ff6c00; }
        .hover\:bg-barayoro:hover { background-color: #ff6c00; }
        .hover\:text-barayoro:hover { color: #ff6c00; }
        
        .gradient-barayoro {
            background: linear-gradient(135deg, #ff6c00 0%, #e05a00 100%);
        }
        
        .prose {
            max-width: 65ch;
            line-height: 1.6;
        }
        .prose h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        .prose h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #1f2937;
        }
        .prose h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            color: #1f2937;
        }
        .prose p {
            margin-bottom: 1rem;
            color: #4b5563;
        }
        .prose ul {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }
        .prose li {
            margin-bottom: 0.5rem;
            color: #4b5563;
        }
        .prose a {
            color: #ff6c00;
            text-decoration: none;
        }
        .prose a:hover {
            text-decoration: underline;
            color: #e05a00;
        }
        .prose strong {
            color: #1f2937;
            font-weight: 600;
        }
        
        .dark .prose h2,
        .dark .prose h3,
        .dark .prose strong {
            color: #f3f4f6;
        }
        .dark .prose p,
        .dark .prose li {
            color: #9ca3af;
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-neutral-900">

    <!-- Header -->
    <header class="bg-white dark:bg-neutral-800 shadow-sm sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 gradient-barayoro rounded-lg flex items-center justify-center">
                    <i class="las la-building text-white text-lg"></i>
                </div>
                <span class="text-xl font-bold text-barayoro">Barayoro</span>
            </a>
            <div class="flex gap-4">
                <a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-300 hover:text-barayoro transition font-medium">
                    <i class="las la-sign-in-alt mr-1"></i>Connexion
                </a>
                <a href="{{ route('register') }}" class="gradient-barayoro text-white px-5 py-2 rounded-lg hover:shadow-lg transition transform hover:-translate-y-0.5">
                    <i class="las la-user-plus mr-1"></i>Inscription
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 py-12 animate-fade-in">
        
        <!-- Fil d'Ariane -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('home') }}" class="hover:text-barayoro transition">Accueil</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-barayoro font-medium">Politique de confidentialité</span>
            </div>
        </div>
        
        <div class="prose prose-gray dark:prose-invert mx-auto">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 gradient-barayoro rounded-2xl mb-4 shadow-lg">
                    <i class="las la-shield-alt text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">Politique de confidentialité</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Dernière mise à jour : {{ date('d/m/Y') }}</p>
            </div>

            <h2>1. Introduction</h2>
            <p>Barayoro (ci-après "nous", "notre", "nos") s'engage à protéger la confidentialité de vos données personnelles. La présente politique explique comment nous collectons, utilisons, partageons et protégeons vos informations.</p>

            <h2>2. Données collectées</h2>
            <p>Nous collectons les informations suivantes :</p>
            <ul>
                <li><i class="fas fa-building text-barayoro mr-2"></i> <strong>Informations d'entreprise :</strong> nom, SIRET, adresse, téléphone, pays</li>
                <li><i class="fas fa-user text-barayoro mr-2"></i> <strong>Informations utilisateur :</strong> nom, email, fonction, mot de passe (chiffré)</li>
                <li><i class="fas fa-tasks text-barayoro mr-2"></i> <strong>Données d'activité :</strong> tâches, projets, clients, factures, dépenses</li>
                <li><i class="fas fa-code text-barayoro mr-2"></i> <strong>Données techniques :</strong> adresse IP, navigateur, logs d'accès</li>
                <li><i class="fas fa-credit-card text-barayoro mr-2"></i> <strong>Données de paiement :</strong> traitées par nos partenaires de paiement sécurisés</li>
            </ul>

            <h2>3. Finalités du traitement</h2>
            <p>Vos données sont utilisées pour :</p>
            <ul>
                <li>Fournir et améliorer nos services</li>
                <li>Gérer votre compte et votre abonnement</li>
                <li>Assurer la sécurité et la maintenance de la plateforme</li>
                <li>Vous informer des mises à jour et nouveautés</li>
                <li>Respecter nos obligations légales et fiscales</li>
            </ul>

            <h2>4. Base légale du traitement</h2>
            <p>Nous traitons vos données sur les bases légales suivantes :</p>
            <ul>
                <li>L'exécution du contrat (utilisation de{{ route('home') }} Barayoro)</li>
                <li>Votre consentement (communications marketing)</li>
                <li>Nos obligations légales (conservation des factures)</li>
                <li>Notre intérêt légitime (amélioration des services)</li>
            </ul>

            <h2>5. Partage des données</h2>
            <p>Nous ne vendons pas vos données. Nous pouvons partager vos données avec :</p>
            <ul>
                <li><strong>Prestataires techniques :</strong> hébergement, paiement, support</li>
                <li><strong>Autorités légales :</strong> si requis par la loi</li>
                <li><strong>Partenaires :</strong> uniquement avec votre consentement explicite</li>
            </ul>

            <h2>6. Hébergement des données</h2>
            <p>Vos données sont hébergées sur des serveurs sécurisés situés en Europe. Nous utilisons des mesures de sécurité techniques et organisationnelles pour protéger vos données.</p>

            <h2>7. Conservation des données</h2>
            <ul>
                <li><strong>Données de compte :</strong> conservées jusqu'à la fermeture du compte</li>
                <li><strong>Factures :</strong> conservées 10 ans (obligation légale)</li>
                <li><strong>Données techniques :</strong> conservées 12 mois maximum</li>
            </ul>

            <h2>8. Vos droits</h2>
            <p>Conformément au RGPD, vous disposez des droits suivants :</p>
            <ul>
                <li><strong>Droit d'accès :</strong> connaître les données que nous détenons</li>
                <li><strong>Droit de rectification :</strong> corriger vos données</li>
                <li><strong>Droit à l'effacement :</strong> demander la suppression de vos données</li>
                <li><strong>Droit à la portabilité :</strong> récupérer vos données</li>
                <li><strong>Droit d'opposition :</strong> refuser certains traitements</li>
                <li><strong>Droit de retirer votre consentement :</strong> à tout moment</li>
            </ul>

            <h2>9. Cookies et technologies similaires</h2>
            <p>Nous utilisons des cookies pour améliorer votre expérience :</p>
            <ul>
                <li><strong>Cookies essentiels :</strong> nécessaires au fonctionnement</li>
                <li><strong>Cookies de performance :</strong> analysent l'utilisation</li>
                <li><strong>Cookies de fonctionnalité :</strong> mémorisent vos préférences</li>
            </ul>
            <p>Vous pouvez gérer vos préférences de cookies dans les paramètres de votre navigateur.</p>

            <h2>10. Sécurité</h2>
            <p>Nous mettons en œuvre des mesures de sécurité avancées :</p>
            <ul>
                <li><i class="fas fa-lock text-barayoro mr-2"></i> Chiffrement des données en transit (HTTPS)</li>
                <li><i class="fas fa-key text-barayoro mr-2"></i> Chiffrement des mots de passe (bcrypt)</li>
                <li><i class="fas fa-database text-barayoro mr-2"></i> Sauvegardes quotidiennes</li>
                <li><i class="fas fa-user-shield text-barayoro mr-2"></i> Contrôle d'accès strict</li>
                <li><i class="fas fa-chart-line text-barayoro mr-2"></i> Audits de sécurité réguliers</li>
            </ul>

            <h2>11. Transferts internationaux</h2>
            <p>Vos données sont hébergées en Europe. En cas de transfert hors UE, nous garantissons un niveau de protection adéquat.</p>

            <h2>12. Protection des données des mineurs</h2>
            <p>Barayoro ne s'adresse pas aux mineurs. Nous ne collectons pas sciemment de données sur des personnes de moins de 18 ans.</p>

            <h2>13. Modifications de la politique</h2>
            <p>Nous pouvons mettre à jour cette politique. Les modifications vous seront notifiées par email ou via la plateforme.</p>

            <h2>14. Contact et DPO</h2>
            <p>Pour toute question concernant vos données personnelles :</p>
            <ul>
                <li><i class="fas fa-envelope text-barayoro mr-2"></i> <strong>Email :</strong> <a href="mailto:dpo@barayoro.com">dpo@barayoro.com</a></li>
                <li><i class="fas fa-phone text-barayoro mr-2"></i> <strong>Téléphone :</strong> +223 92 51 64 05</li>
                <li><i class="fas fa-map-marker-alt text-barayoro mr-2"></i> <strong>Adresse :</strong> Bamako, Mali</li>
            </ul>

            <h2>15. Réclamations</h2>
            <p>Si vous estimez que vos droits ne sont pas respectés, vous avez le droit d'introduire une réclamation auprès de l'autorité de protection des données de votre pays.</p>

            <div class="bg-orange-50 dark:bg-orange-900/20 border-l-4 border-barayoro p-6 rounded-lg mt-8">
                <div class="flex items-start gap-3">
                    <i class="fas fa-shield-alt text-barayoro text-xl mt-0.5"></i>
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 font-medium">Nous accordons une importance capitale à la protection de vos données. Pour toute question, n'hésitez pas à nous contacter.</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <i class="fas fa-check-circle text-green-500 mr-1"></i> Barayoro est conforme au RGPD
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-neutral-700 mt-12">
        <div class="max-w-4xl mx-auto px-4 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
            <div class="flex justify-center gap-6 mb-4">
                <a href="{{ route('terms') }}" class="hover:text-barayoro transition flex items-center gap-1">
                    <i class="las la-file-alt"></i> Conditions
                </a>
                <a href="{{ route('privacy') }}" class="text-barayoro transition flex items-center gap-1">
                    <i class="las la-shield-alt"></i> Confidentialité
                </a>
                <a href="{{ route('contact') }}" class="hover:text-barayoro transition flex items-center gap-1">
                    <i class="las la-envelope"></i> Contact
                </a>
                <a href="{{ route('help.center') }}" class="hover:text-barayoro transition flex items-center gap-1">
                    <i class="las la-question-circle"></i> Aide
                </a>
            </div>
            <p>&copy; {{ date('Y') }} Barayoro. Tous droits réservés.</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                <i class="las la-map-marker-alt mr-1"></i> Bamako, Mali | <i class="las la-phone mr-1"></i> +223 92 51 64 05
            </p>
        </div>
    </footer>
</body>
</html>