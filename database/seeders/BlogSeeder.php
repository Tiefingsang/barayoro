<?php
// database/seeders/BlogSeeder.php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Désactiver les contraintes de clés étrangères temporairement
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Vider les tables dans le bon ordre
        BlogPost::query()->delete();
        BlogCategory::query()->delete();
        
        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Créer les catégories
        $categories = [
            [
                'name' => 'Gestion d\'entreprise',
                'slug' => 'gestion-entreprise',
                'description' => 'Conseils et astuces pour mieux gérer votre entreprise',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Facturation',
                'slug' => 'facturation',
                'description' => 'Tout sur la facturation et les paiements',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Productivité',
                'slug' => 'productivite',
                'description' => 'Améliorez la productivité de votre équipe',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Technologie',
                'slug' => 'technologie',
                'description' => 'Actualités tech et innovations',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'description' => 'Stratégies marketing pour votre business',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::create($category);
        }

        // Récupérer l'utilisateur admin
        $adminUser = User::where('email', 'admin@barayoro.com')->first();
        
        if (!$adminUser) {
            $adminUser = User::first();
        }

        // Créer les articles
        $posts = [
            [
                'title' => 'Comment optimiser la gestion de votre entreprise avec Barayoro',
                'excerpt' => 'Découvrez comment notre solution SaaS peut transformer la gestion quotidienne de votre entreprise et vous faire gagner du temps.',
                'content' => "Barayoro est une solution complète qui centralise toutes vos activités professionnelles. Que ce soit la facturation, la gestion des stocks, ou le suivi de projets, tout est disponible en un seul endroit.

        Pourquoi choisir Barayoro ?

        Notre plateforme a été conçue spécialement pour les entreprises africaines, avec des fonctionnalités adaptées à vos besoins locaux. Nous prenons en compte les spécificités du marché africain comme l'utilisation intensive du mobile, les connexions internet parfois instables, et les méthodes de paiement locales.

        Fonctionnalités clés de Barayoro :

        • Interface intuitive et facile à prendre en main, même pour les utilisateurs non techniques
        • Support client en français 7 jours sur 7, avec une équipe basée sur le continent
        • Tarifs adaptés aux réalités économiques africaines, avec un essai gratuit de 30 jours
        • Paiements sécurisés via Orange Money et Wave, les solutions les plus utilisées en Afrique
        • Mode hors ligne pour travailler même sans connexion internet
        • Synchronisation automatique dès que la connexion est rétablie

        Barayoro vous accompagne dans votre transformation numérique. Notre solution évolue avec vous, que vous soyez une TPE, une PME ou une grande entreprise. Nous proposons des plans adaptés à chaque taille d'entreprise, avec la possibilité d'ajouter des utilisateurs supplémentaires à tout moment.

        La sécurité de vos données est notre priorité absolue. Toutes vos informations sont cryptées et sauvegardées quotidiennement. Vous gardez le contrôle total sur qui peut accéder à quoi grâce à notre système de rôles et permissions.

        Avec Barayoro, gagnez un temps précieux et concentrez-vous sur ce qui compte vraiment : le développement de votre activité.",
                'category_id' => 1,
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'views' => 150,
            ],
            [
                'title' => 'Les avantages de la facturation électronique pour votre entreprise',
                'excerpt' => 'La facturation électronique simplifie vos processus et vous fait gagner du temps. Voici pourquoi vous devriez passer au numérique dès maintenant.',
                'content' => "La facturation électronique est devenue indispensable pour les entreprises modernes. Elle permet de réduire les erreurs, d'accélérer les paiements et de centraliser toutes vos factures en un seul endroit.

        Pourquoi passer à la facturation électronique ?

        Avantages clés pour votre entreprise :

        Réduction des erreurs de saisie : fini les oublis de virgules ou les mauvais calculs. Le système calcule automatiquement les totaux, les taxes et les remises.

        Accélération des paiements : envoyez vos factures instantanément par email. Vos clients les reçoivent en quelques secondes et peuvent payer en ligne directement.

        Centralisation des documents : retrouvez toutes vos factures, devis et avoirs dans un espace unique. Plus besoin de chercher dans des dossiers physiques ou des emails éparpillés.

        Suivi en temps réel : sachez exactement quelles factures sont payées, en attente ou en retard. Recevez des alertes automatiques pour les échéances.

        Conformité légale : respectez les obligations fiscales de votre pays. Barayoro génère des factures conformes aux normes locales.

        Gain de temps considérable : créez des modèles de factures et générez-les en un clic. Dupliquez facilement les factures récurrentes.

        Avec Barayoro, la facturation devient un jeu d'enfant. Notre système s'intègre parfaitement avec votre comptabilité et vous permet d'exporter vos données vers les logiciels les plus courants.

        De plus, vous pouvez accepter les paiements directement depuis la facture via Orange Money ou Wave. Vos clients n'ont plus qu'à cliquer sur un lien et payer en quelques secondes depuis leur téléphone.

        Ne restez pas à l'ère du papier. Rejoignez les milliers d'entreprises qui ont déjà franchi le pas du numérique.",
                'category_id' => 2,
                'status' => 'published',
                'published_at' => now()->subDays(10),
                'views' => 98,
            ],
            [
                'title' => '10 astuces pour booster votre productivité au quotidien',
                'excerpt' => 'Découvrez 10 conseils pratiques pour améliorer la productivité de votre équipe au quotidien.',
                'content' => "La productivité est la clé du succès pour toute entreprise. Voici 10 astuces simples à mettre en place dès aujourd'hui pour optimiser votre temps et celui de vos collaborateurs.

        1. Priorisez vos tâches avec la matrice d'Eisenhower
        Classez vos tâches en quatre catégories : urgent et important, important mais pas urgent, urgent mais pas important, ni urgent ni important. Concentrez-vous d'abord sur ce qui est à la fois urgent et important.

        2. Automatisez les processus répétitifs
        Barayoro vous aide à automatiser vos factures, relances et notifications. Programmez des envois automatiques et libérez-vous des tâches chronophages.

        3. Adoptez la règle des deux minutes
        Si une tâche prend moins de deux minutes, faites-la immédiatement. Ne la mettez pas sur votre liste d'attente.

        4. Utilisez des listes de tâches partagées
        Avec Barayoro, créez des listes de tâches collaboratives. Chaque membre de l'équipe sait exactement quoi faire et quand.

        5. Évitez le multitâche
        Le cerveau humain n'est pas fait pour faire plusieurs choses à la fois. Concentrez-vous sur une tâche à la fois pour être plus efficace.

        6. Planifiez votre journée la veille
        Prenez 10 minutes chaque soir pour planifier le lendemain. Vous commencerez la journée avec une feuille de route claire.

        7. Utilisez des rappels intelligents
        Paramétrez des rappels pour les échéances importantes. Barayoro vous envoie des notifications automatiques.

        8. Déléguez efficacement
        N'essayez pas de tout faire vous-même. Utilisez Barayoro pour assigner des tâches aux bons collaborateurs.

        9. Regroupez les tâches similaires
        Faites toutes vos appels téléphoniques d'affilée, puis toutes vos réponses d'email. Le changement de contexte consomme de l'énergie.

        10. Prenez des pauses régulières
        La technique Pomodoro recommande 25 minutes de travail suivies de 5 minutes de pause. Testez-la et voyez la différence.

        Avec Barayoro, mettez toutes ces astuces en pratique. Notre plateforme a été conçue pour vous aider à organiser votre travail de manière optimale.",
                'category_id' => 3,
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'views' => 210,
            ],
            [
                'title' => 'Pourquoi adopter le mode hors ligne dans votre gestion quotidienne',
                'excerpt' => 'Le mode hors ligne est un atout majeur pour les entreprises en Afrique. Découvrez pourquoi cette fonctionnalité change la donne.',
                'content' => "Avec Barayoro, vous pouvez continuer à travailler même sans connexion internet. Vos données sont synchronisées automatiquement dès que vous retrouvez une connexion. Cette fonctionnalité est particulièrement précieuse dans le contexte africain.

        Les avantages du mode hors ligne pour votre entreprise :

        Travail ininterrompu
        Plus besoin d'annuler une réunion ou de reporter une tâche parce que la connexion est coupée. Barayoro continue de fonctionner, vous permettant de consulter vos données et de créer de nouveaux éléments.

        Sécurité des données renforcée
        Toutes vos modifications sont enregistrées localement sur votre appareil. Aucune donnée n'est perdue, même en cas de coupure soudaine. Dès que la connexion revient, tout est synchronisé.

        Productivité préservée
        Vos équipes continuent de travailler normalement. Les factures peuvent être créées, les tâches assignées, les projets avancés, le tout sans internet.

        Économie de données mobiles
        Le mode hors ligne réduit considérablement votre consommation de données. Les synchronisations sont optimisées pour n'utiliser que le strict nécessaire.

        Autonomie géographique
        Travaillez depuis n'importe où : zones rurales, transports, régions mal desservies. Barayoro vous suit partout, même là où le réseau est absent.

        Expérience utilisateur fluide
        Fini les temps de chargement interminables. Le mode hors ligne offre une réactivité maximale car tout est stocké localement.

        Comment ça fonctionne techniquement ?

        Barayoro utilise une architecture moderne de Progressive Web App (PWA). Lorsque vous installez notre application sur votre appareil, une copie locale est créée. Toutes vos actions sont d'abord enregistrées localement, puis synchronisées en arrière-plan dès que le réseau est disponible.

        Cas d'usage concrets :

        • Un commercial en déplacement qui prépare ses devis dans le train
        • Un gestionnaire de stock qui fait l'inventaire dans un entrepôt sans wifi
        • Une équipe qui travaille dans une zone de travaux avec réseau instable
        • Un utilisateur qui souhaite économiser ses forfaits data

        Avec Barayoro, la connectivité intermittente n'est plus un obstacle. Nous avons pensé à vos réalités terrain.",
                'category_id' => 4,
                'status' => 'published',
                'published_at' => now()->subDays(15),
                'views' => 75,
            ],
            [
                'title' => 'Comment fidéliser vos clients avec un service client exceptionnel',
                'excerpt' => 'La fidélisation client est essentielle pour la croissance de votre entreprise. Voici nos conseils pour y parvenir.',
                'content' => "Un client fidèle est un client rentable. Les études montrent qu'acquérir un nouveau client coûte 5 à 7 fois plus cher que de fidéliser un client existant. Découvrez comment améliorer votre relation client et augmenter votre taux de rétention.

        Stratégies efficaces pour fidéliser vos clients :

        1. Connaissez parfaitement vos clients
        Utilisez Barayoro pour centraliser toutes les informations sur vos clients : historique des achats, préférences, interactions passées. Une connaissance approfondie permet une relation personnalisée.

        2. Répondez rapidement
        Les clients attendent une réponse sous 24 heures. Barayoro vous aide à suivre vos interactions et à ne laisser aucune demande sans réponse.

        3. Personnalisez vos communications
        Utilisez le prénom de vos clients, référez-vous à leurs achats précédents. Barayoro vous permet de créer des modèles d'email personnalisés.

        4. Anticipez les besoins
        Grâce à l'historique, proposez des produits ou services complémentaires avant même que le client n'en exprime le besoin.

        5. Mettez en place un programme de fidélité
        Récompensez vos clients les plus fidèles. Barayoro peut vous aider à suivre les points de fidélité et à générer des avantages automatiquement.

        6. Sollicitez des retours d'expérience
        Demandez l'avis de vos clients après chaque interaction. Utilisez ces informations pour vous améliorer en continu.

        7. Surprenez vos clients
        Un petit geste inattendu (remise, cadeau, message personnalisé) peut faire une grande différence.

        8. Formez votre équipe
        Un personnel bien formé offre un meilleur service. Barayoro propose des guides et tutoriels pour former vos équipes.

        9. Soyez transparent
        En cas de problème, communiquez clairement et proposez des solutions. La transparence renforce la confiance.

        10. Utilisez les bons outils
        Barayoro centralise toutes vos interactions clients : emails, appels, réunions, factures. Tout est traçable et accessible.

        Avec Barayoro, fidéliser devient simple. Notre plateforme vous offre tous les outils pour suivre et améliorer votre relation client, de la première facture au suivi post-vente.",
                'category_id' => 5,
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'views' => 120,
            ],
            [
                'title' => 'Guide complet pour bien démarrer avec Barayoro',
                'excerpt' => 'Tout ce que vous devez savoir pour bien débuter avec notre plateforme SaaS. Un guide pas à pas pour prendre en main toutes les fonctionnalités.',
                'content' => "Ce guide vous accompagne pas à pas dans la prise en main de Barayoro. De l'inscription à l'utilisation avancée, toutes les étapes sont détaillées pour vous permettre de maîtriser rapidement notre plateforme.

        Étape 1 : Créez votre compte
        L'inscription est simple et rapide. Rendez-vous sur barayoro.com et cliquez sur 'S'inscrire'. Remplissez les informations de votre entreprise et de l'administrateur. Vous bénéficiez automatiquement d'un essai gratuit de 30 jours, sans aucun engagement.

        Étape 2 : Configurez votre entreprise
        Une fois connecté, personnalisez votre espace. Ajoutez votre logo, vos coordonnées, vos informations fiscales. Barayoro utilisera ces informations pour générer vos factures et devis.

        Étape 3 : Ajoutez vos collaborateurs
        Invitez vos employés à rejoindre la plateforme. Assignez-leur des rôles : administrateur, gestionnaire ou employé. Chaque rôle a des permissions adaptées.

        Étape 4 : Créez vos premiers clients
        La base clients est le cœur de Barayoro. Ajoutez manuellement vos clients ou importez-les depuis un fichier CSV. Vous pouvez aussi les créer au moment de la facturation.

        Étape 5 : Générez votre première facture
        Créez une facture en quelques clics. Sélectionnez un client, ajoutez des produits ou services, et laissez Barayoro calculer les totaux automatiquement. Envoyez la facture par email directement depuis la plateforme.

        Étape 6 : Configurez vos produits et services
        Créez un catalogue de produits avec prix, descriptions et stocks. Barayoro suivra automatiquement vos niveaux de stock.

        Étape 7 : Lancez vos premiers projets
        Créez un projet, assignez une équipe, définissez des tâches et suivez l'avancement. Les membres de l'équipe peuvent marquer leurs tâches comme terminées.

        Étape 8 : Paramétrez vos modes de paiement
        Activez Orange Money et Wave pour recevoir des paiements en ligne. Entrez vos numéros de compte et laissez vos clients payer directement depuis la facture.

        Étape 9 : Personnalisez vos notifications
        Configurez les alertes par email pour être informé des nouveaux paiements, des échéances de factures ou des tâches à réaliser.

        Étape 10 : Explorez les rapports
        Barayoro génère automatiquement des rapports sur vos ventes, vos projets et votre activité. Utilisez-les pour piloter votre entreprise.

        Étape 11 : Installez l'application mobile
        Ajoutez Barayoro à l'écran d'accueil de votre téléphone. Notre PWA fonctionne comme une application native, avec mode hors ligne inclus.

        Étape 12 : Formez votre équipe
        Utilisez nos tutoriels vidéo et notre centre d'aide pour former vos collaborateurs. Notre support est disponible pour répondre à vos questions.

        Avec Barayoro, vous disposez désormais de tous les outils pour gérer efficacement votre entreprise. Notre équipe vous accompagne tout au long de votre parcours. N'hésitez pas à nous contacter si vous avez besoin d'aide.",
                'category_id' => 1,
                'status' => 'published',
                'published_at' => now()->subDays(20),
                'views' => 320,
            ],
            [
                'title' => 'Comment intégrer les paiements mobile dans votre stratégie commerciale',
                'excerpt' => 'Orange Money et Wave révolutionnent le paiement en Afrique. Apprenez à les intégrer dans votre entreprise.',
                'content' => "Les paiements mobiles connaissent une croissance exponentielle en Afrique. Orange Money et Wave sont désormais des incontournables pour toute entreprise qui souhaite se développer sur le continent. Découvrez comment intégrer ces solutions dans votre stratégie commerciale.

        Pourquoi adopter les paiements mobiles ?

        Accessibilité universelle
        Plus de 80% des adultes en Afrique de l'Ouest ont accès à un compte mobile money. Même les populations non bancarisées peuvent effectuer des transactions.

        Rapidité des transactions
        Les paiements sont instantanés. Plus d'attente de plusieurs jours pour qu'un chèque soit encaissé ou qu'un virement soit traité.

        Sécurité renforcée
        Les transactions sont protégées par code PIN. Réduction significative des risques de vol et de fraude par rapport aux espèces.

        Réduction des coûts
        Moins de frais de gestion d'espèces, moins de risques d'erreur de caisse, une comptabilité simplifiée.

        Traçabilité complète
        Chaque transaction est enregistrée et horodatée. Finis les oublis de paiement ou les litiges non résolus.

        Comment intégrer les paiements mobiles avec Barayoro ?

        Configuration simple
        Dans les paramètres de Barayoro, activez les paiements Orange Money et Wave. Entrez vos numéros de compte marchand.

        Facturation intégrée
        Chaque facture générée inclut automatiquement un bouton 'Payer par mobile'. Vos clients n'ont plus qu'à cliquer.

        Notifications automatiques
        Barayoro vous informe immédiatement quand un paiement est reçu. La facture est automatiquement marquée comme payée.

        Reconciliation automatique
        Plus besoin de faire correspondre manuellement les paiements reçus avec les factures. Barayoro le fait pour vous.

        Rapports détaillés
        Consultez l'historique complet des transactions par client, par période ou par mode de paiement.

        Meilleures pratiques pour réussir

        Communiquez sur les options de paiement
        Affichez clairement que vous acceptez Orange Money et Wave sur votre site, vos factures et vos réseaux sociaux.

        Formez vos équipes
        Assurez-vous que vos vendeurs et votre personnel savent expliquer le processus de paiement mobile à vos clients.

        Testez régulièrement
        Effectuez des transactions de test pour vérifier que tout fonctionne correctement.

        Sécurisez vos comptes
        Utilisez des mots de passe forts et activez toutes les options de sécurité disponibles.

        Avec Barayoro, intégrer les paiements mobiles est un jeu d'enfant. Notre plateforme gère toute la complexité technique pour que vous puissiez vous concentrer sur votre cœur de métier.",
                'category_id' => 2,
                'status' => 'published',
                'published_at' => now()->subDays(8),
                'views' => 95,
            ],
            [
                'title' => 'Gestion de projet : méthodes agiles pour petites équipes',
                'excerpt' => 'Les méthodes agiles ne sont pas réservées aux grandes entreprises. Découvrez comment les appliquer dans votre PME.',
                'content' => "Vous pensez que les méthodes agiles sont uniquement pour les grandes entreprises tech ? Détrompez-vous. Les PME et les petites équipes peuvent aussi bénéficier de ces approches modernes de gestion de projet.

        Les principes agiles adaptés aux petites équipes :

        Livraisons fréquentes et itératives
        Au lieu d'attendre des mois pour voir le résultat final, livrez par petites touches. Appliquez ce principe à vos projets : avancez par étapes et validez régulièrement.

        Collaboration étroite avec les parties prenantes
        Impliquez vos clients ou vos utilisateurs finaux tout au long du projet, pas seulement au début et à la fin.

        Équipes auto-organisées
        Donnez plus d'autonomie à vos équipes. Elles savent mieux que quiconque comment organiser leur travail.

        Communication quotidienne
        Une réunion rapide de 15 minutes chaque matin pour faire le point : ce qui a été fait hier, ce qui est prévu aujourd'hui, les éventuels blocages.

        Barayoro, votre allié agile

        Tableaux Kanban visuels
        Créez des colonnes 'À faire', 'En cours', 'Terminé'. Glissez-déposez vos tâches pour suivre l'avancement en un coup d'œil.

        Suivi des sprints
        Définissez des cycles de travail de 1 à 2 semaines. Barayoro vous aide à planifier et à suivre vos sprints.

        Backlog priorisé
        Listez toutes les tâches à réaliser, triées par ordre de priorité. L'équipe sait exactement sur quoi travailler ensuite.

        Métriques et vélocité
        Mesurez la quantité de travail accomplie à chaque sprint. Utilisez ces données pour mieux planifier les prochains sprints.

        Rétrospectives intégrées
        Créez des sessions de feedback à la fin de chaque sprint. Barayoro conserve l'historique pour vous aider à vous améliorer.

        Cas pratique : lancement d'un nouveau produit

        1. Sprint 1 (1 semaine) : Définition du concept et étude de marché
        2. Sprint 2 : Prototypage rapide et tests utilisateurs
        3. Sprint 3 : Développement des fonctionnalités essentielles
        4. Sprint 4 : Corrections et améliorations basées sur les retours

        À chaque sprint, votre équipe utilise Barayoro pour suivre l'avancement des tâches, communiquer sur les blocages et célébrer les réussites.

        Les avantages pour votre entreprise

        • Réduction des délais de livraison
        • Meilleure qualité grâce aux retours continus
        • Équipes plus motivées (autonomie et responsabilités)
        • Satisfaction client accrue
        • Meilleure visibilité sur l'avancement des projets

        Barayoro a été conçu pour soutenir les méthodes agiles, même pour les petites équipes. Commencez dès aujourd'hui à transformer votre façon de travailler.",
                'category_id' => 3,
                'status' => 'published',
                'published_at' => now()->subDays(12),
                'views' => 87,
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::create([
                'uuid' => (string) Str::uuid(),
                'company_id' => null,
                'author_id' => $adminUser ? $adminUser->id : null,
                'category_id' => $post['category_id'],
                'title' => $post['title'],
                'slug' => Str::slug($post['title']) . '-' . Str::random(6),
                'excerpt' => $post['excerpt'],
                'content' => $post['content'],
                'featured_image' => null,
                'tags' => [],
                'status' => $post['status'],
                'published_at' => $post['published_at'],
                'views' => $post['views'],
            ]);
        }
    }
}