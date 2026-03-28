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
    <title>Politique de confidentialité - Barayoro</title>
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
        .prose ul {
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
            <h1>Politique de confidentialité</h1>
            <p class="text-gray-500 dark:text-gray-400">Dernière mise à jour : {{ date('d/m/Y') }}</p>

            <h2>1. Introduction</h2>
            <p>Barayoro (ci-après "nous", "notre", "nos") s'engage à protéger la confidentialité de vos données personnelles. La présente politique explique comment nous collectons, utilisons, partageons et protégeons vos informations.</p>

            <h2>2. Données collectées</h2>
            <p>Nous collectons les informations suivantes :</p>
            <ul>
                <li><strong>Informations d'entreprise :</strong> nom, SIRET, adresse, téléphone, pays</li>
                <li><strong>Informations utilisateur :</strong> nom, email, fonction, mot de passe (chiffré)</li>
                <li><strong>Données d'activité :</strong> tâches, projets, clients, factures, dépenses</li>
                <li><strong>Données techniques :</strong> adresse IP, navigateur, logs d'accès</li>
                <li><strong>Données de paiement :</strong> traitées par nos partenaires de paiement sécurisés</li>
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
                <li>L'exécution du contrat (utilisation de Barayoro)</li>
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
                <li>Chiffrement des données en transit (HTTPS)</li>
                <li>Chiffrement des mots de passe (bcrypt)</li>
                <li>Sauvegardes quotidiennes</li>
                <li>Contrôle d'accès strict</li>
                <li>Audits de sécurité réguliers</li>
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
                <li><strong>Email :</strong> <a href="mailto:dpo@barayoro.com">dpo@barayoro.com</a></li>
                <li><strong>Téléphone :</strong> +221 33 123 45 67</li>
                <li><strong>Adresse :</strong> Dakar, Sénégal</li>
            </ul>

            <h2>15. Réclamations</h2>
            <p>Si vous estimez que vos droits ne sont pas respectés, vous avez le droit d'introduire une réclamation auprès de la CNIL (ou de l'autorité de protection des données de votre pays).</p>

            <div class="bg-gray-100 dark:bg-neutral-800 p-6 rounded-lg mt-8">
                <p class="text-sm text-gray-600 dark:text-gray-400">Nous accordons une importance capitale à la protection de vos données. Pour toute question, n'hésitez pas à nous contacter.</p>
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
