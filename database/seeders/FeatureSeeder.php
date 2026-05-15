<?php
// database/seeders/FeatureSeeder.php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        // Vider la table avant d'insérer
        Feature::truncate();

        $features = [
            [
                'title' => 'Gestion des factures',
                'description' => 'Créez et envoyez des factures professionnelles en quelques clics. Suivez les paiements et gérez les relances automatiquement.',
                'icon' => 'las la-file-invoice',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Suivi des stocks',
                'description' => 'Gérez vos produits, suivez les entrées et sorties, recevez des alertes quand le stock est bas.',
                'icon' => 'las la-boxes',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Gestion de projets',
                'description' => 'Planifiez, organisez et suivez vos projets. Assignez des tâches et suivez l\'avancement en temps réel.',
                'icon' => 'las la-project-diagram',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Mode hors ligne',
                'description' => 'Travaillez même sans connexion internet. Vos données se synchronisent automatiquement quand vous êtes connecté.',
                'icon' => 'las la-wifi',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Tableau de bord personnalisé',
                'description' => 'Visualisez tous vos indicateurs clés sur un tableau de bord personnalisable selon vos besoins.',
                'icon' => 'las la-chart-line',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'Gestion des équipes',
                'description' => 'Gérez vos employés, leurs rôles et permissions. Suivez leurs performances et temps de travail.',
                'icon' => 'las la-users',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'title' => 'Paiements sécurisés',
                'description' => 'Acceptez les paiements en ligne via Orange Money, Wave, carte bancaire et virement.',
                'icon' => 'las la-credit-card',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'title' => 'Rapports et analyses',
                'description' => 'Générez des rapports détaillés sur vos ventes, dépenses et performances. Exportez en PDF/Excel.',
                'icon' => 'las la-chart-pie',
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'title' => 'Applications mobiles',
                'description' => 'Accédez à votre entreprise depuis n\'importe où avec nos applications iOS et Android.',
                'icon' => 'las la-mobile-alt',
                'is_active' => true,
                'sort_order' => 9,
            ],
        ];

        foreach ($features as $feature) {
            Feature::create($feature);
        }
    }
}