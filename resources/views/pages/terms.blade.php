<!DOCTYPE html>
<html dir="ltr" lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Conditions d'utilisation - Barayoro</title>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
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
        }
        .prose h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }
        .prose p {
            margin-bottom: 1rem;
        }
        .prose ul, .prose ol {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }
        .prose li {
            margin-bottom: 0.5rem;
        }
        .prose a {
            color: #3b82f6;
            text-decoration: none;
        }
        .prose a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-neutral-900">

    <!-- Header -->
    <header class="bg-white dark:bg-neutral-800 shadow-sm sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-blue-600 dark:text-blue-400">Barayoro</a>
            <div class="flex gap-4">
                <a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-300 hover:text-blue-600">Connexion</a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Inscription</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 py-12">
        <div class="prose prose-gray dark:prose-invert mx-auto">
            <h1>Conditions générales d'utilisation</h1>
            <p class="text-gray-500 dark:text-gray-400">Dernière mise à jour : {{ date('d/m/Y') }}</p>

            <h2>1. Préambule</h2>
            <p>Barayoro est une plateforme SaaS de gestion d'entreprise qui permet aux entreprises de gérer leurs opérations quotidiennes (utilisateurs, tâches, projets, facturation, stock, etc.). Les présentes conditions générales d'utilisation (CGU) régissent l'accès et l'utilisation de la plateforme Barayoro.</p>

            <h2>2. Acceptation des conditions</h2>
            <p>En créant un compte sur Barayoro, vous acceptez sans réserve les présentes conditions. Si vous n'acceptez pas ces conditions, vous ne pouvez pas utiliser nos services.</p>

            <h2>3. Création de compte</h2>
            <p>Pour utiliser Barayoro, vous devez créer un compte entreprise. Vous vous engagez à fournir des informations exactes, complètes et à jour. Vous êtes responsable de la confidentialité de vos identifiants de connexion.</p>

            <h2>4. Abonnement et paiement</h2>
            <p>Barayoro propose un abonnement annuel avec les modalités suivantes :</p>
            <ul>
                <li><strong>Essai gratuit :</strong> 30 jours d'essai gratuit, limité à 5 utilisateurs</li>
                <li><strong>Premium annuel :</strong> 490 €/an, utilisateurs illimités</li>
            </ul>
            <p>Le paiement est effectué annuellement et est non remboursable sauf cas prévus par la loi.</p>

            <h2>5. Utilisation de la plateforme</h2>
            <p>Vous vous engagez à utiliser Barayoro conformément à la loi et aux présentes conditions. Vous êtes seul responsable des données que vous saisissez et des activités réalisées sur votre compte.</p>
            <p>Il est interdit de :</p>
            <ul>
                <li>Utiliser la plateforme pour des activités illégales</li>
                <li>Tenter d'accéder à des comptes d'autres entreprises</li>
                <li>Modifier, décompiler ou désassembler le logiciel</li>
                <li>Utiliser des robots ou scripts automatisés</li>
            </ul>

            <h2>6. Propriété intellectuelle</h2>
            <p>Barayoro est une création exclusive de Masadigitale. Tous les droits de propriété intellectuelle (logiciels, designs, marques, etc.) nous appartiennent. Vous bénéficiez d'une licence d'utilisation non exclusive pour utiliser la plateforme.</p>

            <h2>7. Confidentialité des données</h2>
            <p>La protection de vos données est essentielle. Nous traitons vos données conformément à notre <a href="{{ route('privacy') }}">Politique de confidentialité</a>. Vous restez propriétaire de vos données.</p>

            <h2>8. Responsabilité</h2>
            <p>Barayoro est fourni "en l'état". Nous ne garantissons pas que le service sera ininterrompu ou sans erreur. Nous ne sommes pas responsables des pertes de données, des interruptions de service ou des dommages indirects.</p>

            <h2>9. Suspension et résiliation</h2>
            <p>Nous nous réservons le droit de suspendre ou résilier votre compte en cas de violation des présentes conditions, après notification. Vous pouvez résilier votre compte à tout moment depuis l'interface.</p>

            <h2>10. Modification des conditions</h2>
            <p>Nous pouvons modifier ces conditions à tout moment. Les modifications vous seront notifiées par email et prendront effet 30 jours après notification.</p>

            <h2>11. Loi applicable</h2>
            <p>Les présentes conditions sont régies par le droit sénégalais. Tout litige sera soumis aux tribunaux compétents de Dakar.</p>

            <h2>12. Contact</h2>
            <p>Pour toute question concernant ces conditions, contactez-nous :</p>
            <ul>
                <li><strong>Email :</strong> <a href="mailto:contact@barayoro.com">contact@barayoro.com</a></li>
                <li><strong>Téléphone :</strong> +221 33 123 45 67</li>
                <li><strong>Adresse :</strong> Dakar, Sénégal</li>
            </ul>

            <div class="bg-gray-100 dark:bg-neutral-800 p-6 rounded-lg mt-8">
                <p class="text-sm text-gray-600 dark:text-gray-400">En utilisant Barayoro, vous acceptez ces conditions générales d'utilisation.</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-neutral-700 mt-12">
        <div class="max-w-4xl mx-auto px-4 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
            <p>&copy; {{ date('Y') }} Barayoro. Tous droits réservés.</p>
            <div class="flex justify-center gap-4 mt-2">
                <a href="{{ route('terms') }}" class="hover:text-blue-600">Conditions d'utilisation</a>
                <a href="{{ route('privacy') }}" class="hover:text-blue-600">Politique de confidentialité</a>
                <a href="{{ route('contact') }}" class="hover:text-blue-600">Contact</a>
            </div>
        </div>
    </footer>
</body>
</html>
