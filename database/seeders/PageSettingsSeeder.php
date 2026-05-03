<?php
// database/seeders/PageSettingsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\Faq;

class PageSettingsSeeder extends Seeder
{
    public function run()
    {
        // Paramètres généraux
        Setting::setValue('hero_description', 'Barayoro est la solution SaaS tout-en-un pour gérer vos ventes, factures, stocks, projets et équipes. Accédez à vos données partout, même hors ligne.');
        Setting::setValue('features_title', 'Tout ce dont votre entreprise a besoin');
        Setting::setValue('features_subtitle', 'Une solution complète pour gérer l\'ensemble de vos activités professionnelles');
        Setting::setValue('meta_description', 'Barayoro - Solution SaaS de gestion d\'entreprise. Facturation, stocks, projets, équipes. Essai gratuit 30 jours.');
        
        // FAQ par défaut
        $faqs = [
            ['question' => 'Qu\'est-ce que Barayoro ?', 'answer' => 'Barayoro est une solution SaaS complète pour la gestion d\'entreprise, développée par Masadigitale.', 'category' => 'général', 'sort_order' => 1],
            ['question' => 'Comment démarrer avec Barayoro ?', 'answer' => 'Vous pouvez vous inscrire gratuitement pour un essai de 30 jours, puis choisir le plan qui correspond à vos besoins.', 'category' => 'démarrage', 'sort_order' => 2],
            ['question' => 'Puis-je utiliser Barayoro hors ligne ?', 'answer' => 'Oui, Barayoro fonctionne en mode hors ligne et synchronise automatiquement vos données lorsque la connexion est rétablie.', 'category' => 'technique', 'sort_order' => 3],
            ['question' => 'Quels moyens de paiement acceptez-vous ?', 'answer' => 'Nous acceptons les cartes bancaires, Orange Money, Wave, et les virements bancaires.', 'category' => 'paiement', 'sort_order' => 4],
            ['question' => 'Comment gérer plusieurs utilisateurs ?', 'answer' => 'Vous pouvez ajouter des utilisateurs depuis le menu "Utilisateurs" et leur assigner des rôles spécifiques.', 'category' => 'gestion', 'sort_order' => 5],
            ['question' => 'Barayoro est-il sécurisé ?', 'answer' => 'Oui, nous utilisons le chiffrement SSL, l\'authentification à deux facteurs et des sauvegardes quotidiennes.', 'category' => 'sécurité', 'sort_order' => 6],
        ];
        
        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}