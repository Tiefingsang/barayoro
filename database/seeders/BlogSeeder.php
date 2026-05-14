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
                'content' => '<p>Barayoro est une solution complète qui centralise toutes vos activités professionnelles. Que ce soit la facturation, la gestion des stocks, ou le suivi de projets, tout est disponible en un seul endroit.</p>
                              <h2>Pourquoi choisir Barayoro ?</h2>
                              <p>Notre plateforme a été conçue pour les entreprises africaines, avec des fonctionnalités adaptées à vos besoins locaux comme le paiement via Orange Money et le mode hors ligne.</p>
                              <ul><li>Interface intuitive</li><li>Support en français</li><li>Tarifs adaptés</li></ul>',
                'category_id' => 1,
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'views' => 150,
            ],
            [
                'title' => 'Les avantages de la facturation électronique',
                'excerpt' => 'La facturation électronique simplifie vos processus et vous fait gagner du temps. Voici pourquoi vous devriez passer au numérique.',
                'content' => '<p>La facturation électronique est devenue indispensable pour les entreprises modernes. Elle permet de réduire les erreurs, d\'accélérer les paiements et de centraliser toutes vos factures.</p>
                              <h2>Avantages clés</h2>
                              <p>Gagnez du temps, réduisez les coûts et améliorez votre trésorerie avec la facturation électronique.</p>',
                'category_id' => 2,
                'status' => 'published',
                'published_at' => now()->subDays(10),
                'views' => 98,
            ],
            [
                'title' => '10 astuces pour booster votre productivité',
                'excerpt' => 'Découvrez 10 conseils pratiques pour améliorer la productivité de votre équipe au quotidien.',
                'content' => '<p>La productivité est la clé du succès. Voici 10 astuces simples à mettre en place dès aujourd\'hui pour optimiser votre temps et celui de vos collaborateurs.</p>
                              <h2>1. Priorisez vos tâches</h2>
                              <p>Utilisez la matrice d\'Eisenhower pour distinguer l\'urgent de l\'important.</p>
                              <h2>2. Automatisez les processus répétitifs</h2>
                              <p>Barayoro vous aide à automatiser vos factures et relances.</p>',
                'category_id' => 3,
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'views' => 210,
            ],
            [
                'title' => 'Pourquoi adopter le mode hors ligne dans votre gestion ?',
                'excerpt' => 'Le mode hors ligne est un atout majeur pour les entreprises en Afrique. Découvrez pourquoi.',
                'content' => '<p>Avec Barayoro, vous pouvez continuer à travailler même sans connexion internet. Vos données sont synchronisées automatiquement dès que vous retrouvez une connexion.</p>
                              <h2>Avantages du mode hors ligne</h2>
                              <ul><li>Travail ininterrompu</li><li>Sécurité des données</li><li>Productivité préservée</li></ul>',
                'category_id' => 4,
                'status' => 'published',
                'published_at' => now()->subDays(15),
                'views' => 75,
            ],
            [
                'title' => 'Comment fidéliser vos clients avec un bon service',
                'excerpt' => 'La fidélisation client est essentielle pour la croissance de votre entreprise. Voici nos conseils.',
                'content' => '<p>Un client fidèle est un client rentable. Découvrez comment améliorer votre relation client et augmenter votre taux de rétention.</p>
                              <h2>Stratégies efficaces</h2>
                              <p>Utilisez Barayoro pour suivre vos interactions clients et personnaliser vos communications.</p>',
                'category_id' => 5,
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'views' => 120,
            ],
            [
                'title' => 'Guide complet pour bien démarrer avec Barayoro',
                'excerpt' => 'Tout ce que vous devez savoir pour bien débuter avec notre plateforme SaaS.',
                'content' => '<p>Ce guide vous accompagne pas à pas dans la prise en main de Barayoro. De l\'inscription à l\'utilisation avancée, toutes les étapes sont détaillées.</p>
                              <h2>Étape 1 : Créez votre compte</h2>
                              <p>Inscrivez-vous gratuitement pour un essai de 30 jours.</p>
                              <h2>Étape 2 : Configurez votre entreprise</h2>
                              <p>Renseignez vos informations et personnalisez votre espace.</p>',
                'category_id' => 1,
                'status' => 'published',
                'published_at' => now()->subDays(20),
                'views' => 320,
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