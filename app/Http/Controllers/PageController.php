<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use App\Models\Feature;
use App\Models\PricingPlan;
use App\Models\Company;
use App\Models\PartnerLogo;
use App\Models\ContactMessage;
use App\Models\Setting;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    /**
     * Page d'accueil dynamique
     */
    public function home()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        // Récupérer les offres d'emploi actives
        $jobOffers = JobOffer::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->with('company')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Types d'entreprises pour le filtre
        $companyTypes = Company::whereNotNull('type')
            ->distinct()
            ->select('type as name', DB::raw("LOWER(REPLACE(REPLACE(type, ' ', '-'), 'é', 'e')) as slug"))
            ->get();

        // Fonctionnalités
        $features = Feature::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Plans tarifaires
        $pricingPlans = PricingPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Statistiques
        $totalCompaniesCount = Company::count();
        
        // Entreprises de confiance (avec logo)
        $trustedCompanies = Company::whereNotNull('logo')
            ->take(3)
            ->get();
        
        // Logos partenaires
        $partnerLogos = PartnerLogo::where('is_active', true)->get();

        // Paramètres globaux
        $settings = [
            'hero_description' => Setting::getValue('hero_description', 'Barayoro est la solution SaaS tout-en-un pour gérer vos ventes, factures, stocks, projets et équipes. Accédez à vos données partout, même hors ligne.'),
            'meta_description' => Setting::getValue('meta_description', 'Barayoro est la solution SaaS tout-en-un pour gérer votre entreprise. Facturation, stocks, projets, équipes. Essai gratuit 30 jours.'),
        ];
        
        $featuresTitle = Setting::getValue('features_title', 'Tout ce dont votre entreprise a besoin');
        $featuresSubtitle = Setting::getValue('features_subtitle', 'Une solution complète pour gérer l\'ensemble de vos activités professionnelles');

        return view('welcome', compact(
            'jobOffers',
            'companyTypes',
            'features',
            'pricingPlans',
            'totalCompaniesCount',
            'trustedCompanies',
            'partnerLogos',
            'settings',
            'featuresTitle',
            'featuresSubtitle'
        ));
    }

    /**
     * Page À propos
     */
    public function about()
    {
        $stats = [
            'years_experience' => 5,
            'customers' => Company::count(),
            'countries' => 8,
            'projects' => JobOffer::count(),
        ];
        
        return view('pages.about', compact('stats'));
    }

    /**
     * Page FAQ (dynamique depuis base de données)
     */
    public function faq()
    {
        // Récupérer les FAQ depuis la base de données
        $faqs = Faq::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        // Si pas de FAQ en base, utiliser les données par défaut
        if ($faqs->isEmpty()) {
            $faqs = collect([
                (object)[
                    'question' => 'Qu\'est-ce que Barayoro ?',
                    'answer' => 'Barayoro est une solution SaaS complète pour la gestion d\'entreprise, développée par Masadigitale.',
                    'category' => 'général'
                ],
                (object)[
                    'question' => 'Comment démarrer avec Barayoro ?',
                    'answer' => 'Vous pouvez vous inscrire gratuitement pour un essai de 30 jours, puis choisir le plan qui correspond à vos besoins.',
                    'category' => 'démarrage'
                ],
                (object)[
                    'question' => 'Puis-je utiliser Barayoro hors ligne ?',
                    'answer' => 'Oui, Barayoro fonctionne en mode hors ligne et synchronise automatiquement vos données lorsque la connexion est rétablie.',
                    'category' => 'technique'
                ],
                (object)[
                    'question' => 'Quels moyens de paiement acceptez-vous ?',
                    'answer' => 'Nous acceptons les cartes bancaires, Orange Money, Wave, et les virements bancaires.',
                    'category' => 'paiement'
                ],
                (object)[
                    'question' => 'Comment gérer plusieurs utilisateurs ?',
                    'answer' => 'Vous pouvez ajouter des utilisateurs depuis le menu "Utilisateurs" et leur assigner des rôles spécifiques.',
                    'category' => 'gestion'
                ],
                (object)[
                    'question' => 'Barayoro est-il sécurisé ?',
                    'answer' => 'Oui, nous utilisons le chiffrement SSL, l\'authentification à deux facteurs et des sauvegardes quotidiennes.',
                    'category' => 'sécurité'
                ],
            ]);
        }

        $categories = $faqs->pluck('category')->unique();

        return view('pages.faq', compact('faqs', 'categories'));
    }

    /**
     * Page Tarifs (dynamique depuis base de données)
     */
    public function pricing()
    {
        // Récupérer les plans depuis la base de données
        $plans = PricingPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        // Si pas de plans en base, utiliser les données par défaut
        if ($plans->isEmpty()) {
            $plans = collect([
                (object)[
                    'name' => 'Essai',
                    'price' => 0,
                    'period' => '30 jours',
                    'features' => ['5 utilisateurs', 'Toutes les fonctionnalités', 'Support par email', 'Stockage: 1GB'],
                    'button_text' => 'Commencer l\'essai',
                    'button_url' => route('register'),
                    'is_popular' => false,
                    'icon' => 'las la-gem',
                ],
                (object)[
                    'name' => 'Premium',
                    'price' => 490,
                    'period' => 'an',
                    'features' => ['Utilisateurs illimités', 'Toutes les fonctionnalités', 'Support prioritaire 24/7', 'Stockage: 100GB', 'API dédiée', 'Formation incluse'],
                    'button_text' => 'Choisir Premium',
                    'button_url' => route('register'),
                    'is_popular' => true,
                    'icon' => 'las la-crown',
                ],
            ]);
        }

        $currentPlan = Auth::check() ? Auth::user()->company->subscription_status ?? null : null;

        return view('pages.pricing', compact('plans', 'currentPlan'));
    }

    /**
     * Page Conditions d'utilisation
     */
    public function terms()
    {
        $content = Setting::getValue('terms_content', '');
        $lastUpdated = Setting::getValue('terms_last_updated', now()->format('d/m/Y'));
        
        return view('pages.terms', compact('content', 'lastUpdated'));
    }

    /**
     * Page Politique de confidentialité
     */
    public function privacy()
    {
        $content = Setting::getValue('privacy_content', '');
        $lastUpdated = Setting::getValue('privacy_last_updated', now()->format('d/m/Y'));
        
        return view('pages.privacy', compact('content', 'lastUpdated'));
    }

    /**
     * Page Contact avec formulaire
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Traiter le formulaire de contact
     */
    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // Sauvegarder en base
        $contact = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'subject' => $validated['subject'] ?? 'Message depuis le site',
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'pending',
        ]);

        // Envoyer une notification par email
        try {
            Mail::send('emails.contact-notification', $validated, function($mail) use ($validated) {
                $mail->to(config('mail.admin_address', 'admin@barayoro.com'))
                    ->subject('Nouveau message de contact - Barayoro')
                    ->replyTo($validated['email'], $validated['name']);
            });
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email contact: ' . $e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.'
            ]);
        }

        return redirect()->route('contact')->with('success', 'Votre message a été envoyé avec succès !');
    }

    /**
     * Page Centre d'aide
     */
    public function helpCenter()
    {
        $popularArticles = HelpArticle::where('is_active', true)
            ->where('is_popular', true)
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();
        
        $categories = HelpCategory::where('is_active', true)
            ->with(['articles' => function($q) {
                $q->where('is_active', true)->limit(5);
            }])
            ->get();
        
        return view('pages.help-center', compact('popularArticles', 'categories'));
    }

    /**
     * Page de paiement
     */
    public function payment()
    {
        return view('pages.payment');
    }

    /**
     * Page maintenance
     */
    public function maintenance()
    {
        return view('pages.maintenance');
    }

    /**
     * Page coming soon
     */
    public function comingSoon()
    {
        return view('pages.coming-soon');
    }
}