<?php
// database/seeders/PricingPlanSeeder.php

namespace Database\Seeders;

use App\Models\PricingPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PricingPlanSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PricingPlan::query()->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $plans = [
            [
                'name' => 'Gratuit',
                'subtitle' => 'Pour démarrer',
                'price' => 0,
                'period' => '30 jours',
                'features' => json_encode([  // ← Encoder en JSON
                    'Jusqu\'à 5 utilisateurs',
                    'Toutes les fonctionnalités de base',
                    'Support par email',
                    'Stockage: 1GB',
                    'Facturation en ligne',
                    '10 factures par mois',
                ]),
                'button_text' => 'Commencer l\'essai',
                'button_url' => '/register',
                'icon' => 'las la-gem',
                'is_popular' => false,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Starter',
                'subtitle' => 'Pour les petites entreprises',
                'price' => 15000,
                'period' => 'mois',
                'features' => json_encode([
                    'Jusqu\'à 10 utilisateurs',
                    'Toutes les fonctionnalités',
                    'Support prioritaire',
                    'Stockage: 20GB',
                    'Facturation illimitée',
                    'Rapports avancés',
                ]),
                'button_text' => 'Choisir Starter',
                'button_url' => '/register',
                'icon' => 'las la-rocket',
                'is_popular' => false,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Business',
                'subtitle' => 'Pour les PME en croissance',
                'price' => 45000,
                'period' => 'mois',
                'features' => json_encode([
                    'Utilisateurs illimités',
                    'Toutes les fonctionnalités',
                    'Support prioritaire 24/7',
                    'Stockage: 100GB',
                    'API complète',
                    'Rapports personnalisés',
                    'Intégrations illimitées',
                    'Formation incluse',
                ]),
                'button_text' => 'Choisir Business',
                'button_url' => '/register',
                'icon' => 'las la-chart-line',
                'is_popular' => true,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Annuel',
                'subtitle' => 'Économisez 2 mois',
                'price' => 490000,
                'period' => 'an',
                'features' => json_encode([
                    'Tout le plan Business',
                    '2 mois gratuits',
                    'Support prioritaire',
                    'Stockage: 200GB',
                    'Formation offerte',
                ]),
                'button_text' => 'Choisir Annuel',
                'button_url' => '/register',
                'icon' => 'las la-calendar-check',
                'is_popular' => false,
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            PricingPlan::create($plan);
        }
    }
}