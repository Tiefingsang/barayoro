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
    <title>Conditions d'utilisation - Barayoro</title>
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
        .prose ul, .prose ol {
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
                <span class="text-barayoro font-medium">Conditions d'utilisation</span>
            </div>
        </div>
        
        <div class="prose prose-gray dark:prose-invert mx-auto">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 gradient-barayoro rounded-2xl mb-4 shadow-lg">
                    <i class="las la-file-alt text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">Conditions générales d'utilisation</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Dernière mise à jour : {{ date('d/m/Y') }}</p>
            </div>

            <h2>1. Préambule</h2>{{ route('home') }}
            <p>Barayoro est une plateforme SaaS de gestion d'entreprise qui permet aux entreprises de gérer leurs opérations quotidiennes (utilisateurs, tâches, projets, facturation, stock, etc.). Les présentes conditions générales d'utilisation (CGU) régissent l'accès et l'utilisation de la plateforme Barayoro.</p>

            <h2>2. Acceptation des conditions</h2>
            <p>En créant un compte sur Barayoro, vous acceptez sans réserve les présentes conditions. Si vous n'acceptez pas ces conditions, vous ne pouvez pas utiliser nos services.</p>

            <h2>3. Création de compte</h2>
            <p>Pour utiliser Barayoro, vous devez créer un compte entreprise. Vous vous engagez à fournir des informations exactes, complètes et à jour. Vous êtes responsable de la confidentialité de vos identifiants de connexion.</p>

            <h2>4. Abonnement et paiement</h2>
            <p>Barayoro propose un abonnement annuel avec les modalités suivantes :</p>
            <ul>
                <li><i class="fas fa-gem text-barayoro mr-2"></i> <strong>Essai gratuit :</strong> 30 jours d'essai gratuit, limité à 5 utilisateurs</li>
                <li><i class="fas fa-crown text-barayoro mr-2"></i> <strong>Premium annuel :</strong> 49 000 FCFA / an, utilisateurs illimités</li>
            </ul>
            <p>Le paiement est effectué annuellement via Orange Money ou Wave et est non remboursable sauf cas prévus par la loi.</p>

            <h2>5. Utilisation de la plateforme</h2>
            <p>Vous vous engagez à utiliser Barayoro conformément à la loi et aux présentes conditions. Vous êtes seul responsable des données que vous saisissez et des activités réalisées sur votre compte.</p>
            <p>Il est interdit de :</p>
            <ul>
                <li>Utiliser la plateforme pour des activités illégales</li>
                <li>Tenter d'accéder à des comptes d'autres entreprises</li>
                <li>Modifier, décompiler ou désassembler le logiciel</li>
                <li>Utiliser des robots ou scripts automatisés</li>
                <li>Publier du contenu offensant ou diffamatoire</li>
            </ul>

            <h2>6. Propriété intellectuelle</h2>
            <p>Barayoro est une création exclusive de <strong>Masadigitale</strong>. Tous les droits de propriété intellectuelle (logiciels, designs, marques, etc.) nous appartiennent. Vous bénéficiez d'une licence d'utilisation non exclusive pour utiliser la plateforme.</p>

            <h2>7. Confidentialité des données</h2>
            <p>La protection de vos données est essentielle. Nous traitons vos données conformément à notre <a href="{{ route('privacy') }}">Politique de confidentialité</a>. Vous restez propriétaire de vos données.</p>

            <h2>8. Responsabilité</h2>
            <p>Barayoro est fourni "en l'état". Nous ne garantissons pas que le service sera ininterrompu ou sans erreur. Nous ne sommes pas responsables des pertes de données, des interruptions de service ou des dommages indirects.</p>

            <h2>9. Suspension et résiliation</h2>
            <p>Nous nous réservons le droit de suspendre ou résilier votre compte en cas de violation des présentes conditions, après notification. Vous pouvez résilier votre compte à tout moment depuis l'interface.</p>

            <h2>10. Modification des conditions</h2>
            <p>Nous pouvons modifier ces conditions à tout moment. Les modifications vous seront notifiées par email et prendront effet 30 jours après notification.</p>

            <h2>11. Loi applicable</h2>
            <p>Les présentes conditions sont régies par le droit malien. Tout litige sera soumis aux tribunaux compétents de Bamako.</p>

            <h2>12. Contact</h2>
            <p>Pour toute question concernant ces conditions, contactez-nous :</p>
            <ul>
                <li><i class="fas fa-envelope text-barayoro mr-2"></i> <strong>Email :</strong> <a href="mailto:contact@barayoro.com">contact@barayoro.com</a></li>
                <li><i class="fas fa-phone text-barayoro mr-2"></i> <strong>Téléphone :</strong> +223 92 51 64 05</li>
                <li><i class="fas fa-map-marker-alt text-barayoro mr-2"></i> <strong>Adresse :</strong> Bamako, Mali</li>
            </ul>

            <div class="bg-orange-50 dark:bg-orange-900/20 border-l-4 border-barayoro p-6 rounded-lg mt-8">
                <div class="flex items-start gap-3">
                    <i class="fas fa-gavel text-barayoro text-xl mt-0.5"></i>
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 font-medium">En utilisant Barayoro, vous acceptez ces conditions générales d'utilisation.</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <i class="fas fa-check-circle text-green-500 mr-1"></i> Dernière version validée le {{ date('d/m/Y') }}
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
                <a href="{{ route('terms') }}" class="text-barayoro transition flex items-center gap-1">
                    <i class="las la-file-alt"></i> Conditions
                </a>
                <a href="{{ route('privacy') }}" class="hover:text-barayoro transition flex items-center gap-1">
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
                <i class="las la-map-marker-alt mr-1"></i> Bamako, Mali | <i class="las la-phone mr-1"></i> +223 92 51 64 05 | <i class="las la-envelope mr-1"></i> contact@barayoro.com
            </p>
        </div>
    </footer>
</body>
</html>