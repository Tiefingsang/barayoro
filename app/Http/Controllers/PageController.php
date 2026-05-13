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
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class PageController extends Controller
{
    /**
     * Page d'accueil dynamique
     */
   /**
 * Page d'accueil dynamique
 */
public function home()
{
  

    // Récupérer les offres d'emploi actives
    $jobOffers = JobOffer::where('is_active', true)
        ->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        })
        ->with('company')
        ->orderBy('created_at', 'desc')
        ->take(5)
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
    
    // Entreprises de confiance - Récupère les entreprises avec ou sans logo
    $trustedCompanies = Company::where('is_active', true)
        ->take(6)
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
                    'price' => 49000,
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
 * Page Fonctionnalités
 */
public function features()
{
    $features = Feature::where('is_active', true)
        ->orderBy('sort_order')
        ->get();
    
    return view('pages.features', compact('features'));
}

/**
 * Page Offres d'emploi
 */
public function jobs()
{
    $jobOffers = JobOffer::where('is_active', true)
        ->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        })
        ->with('company')
        ->orderBy('created_at', 'desc')
        ->paginate(10);
    
    $companyTypes = Company::whereNotNull('type')
        ->distinct()
        ->select('type as name', DB::raw("LOWER(REPLACE(REPLACE(type, ' ', '-'), 'é', 'e')) as slug"))
        ->get();
    
    return view('pages.jobs', compact('jobOffers', 'companyTypes'));
}

/**
 * Détail offre d'emploi
 */
public function jobDetail($id)
{
    $job = JobOffer::with('company')->findOrFail($id);
    return view('pages.job-detail', compact('job'));
}

    /**
     * Traiter le formulaire de contact
     */
    /* public function sendContact(Request $request)
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
    } */
public function sendContact(Request $request)
{
    // 1. Vérification anti-spam (rate limiting)
    $throttleKey = 'contact:' . $request->ip();
    
    if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
        $seconds = RateLimiter::availableIn($throttleKey);
        $minutes = ceil($seconds / 60);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => "Trop de messages envoyés. Veuillez réessayer dans {$minutes} minute(s)."
            ], 429);
        }
        
        return back()->with('error', "Trop de messages envoyés. Veuillez réessayer dans {$minutes} minute(s).")
            ->withInput();
    }
    
    // 2. Validation stricte des entrées (sans subject car pas dans le formulaire)
    $validated = $request->validate([
        'name' => 'required|string|max:100|regex:/^[a-zA-Z\s\-\'àâäéèêëïîôöùûüçÀÂÄÉÈÊËÏÎÔÖÙÛÜÇ]+$/u',
        'email' => 'required|email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s]{8,20}$/',
        'message' => 'required|string|min:10|max:5000',
    ]);
    
    // 3. Anti-bot (honeypot - champ caché à ajouter dans le formulaire)
    $honeypot = $request->input('_website', '');
    if (!empty($honeypot)) {
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Message envoyé']);
        }
        return redirect()->route('contact')->with('success', 'Message envoyé');
    }
    
    // 4. Nettoyage anti-XSS
    $cleanedName = htmlspecialchars(trim($validated['name']), ENT_QUOTES, 'UTF-8');
    $cleanedEmail = filter_var(trim($validated['email']), FILTER_SANITIZE_EMAIL);
    $cleanedPhone = $validated['phone'] ? preg_replace('/[^0-9+]/', '', $validated['phone']) : null;
    $cleanedMessage = htmlspecialchars(trim($validated['message']), ENT_QUOTES, 'UTF-8');
    
    // Sujet par défaut (pas de champ subject dans le formulaire)
    $cleanedSubject = 'Message depuis le site Barayoro';
    
    // 5. Vérification anti-spam (emails temporaires)
    $temporaryDomains = ['tempmail.com', '10minutemail.com', 'guerrillamail.com', 'mailinator.com', 'yopmail.com', 'temp-mail.org'];
    $emailDomain = substr(strrchr($cleanedEmail, "@"), 1);
    
    if (in_array($emailDomain, $temporaryDomains)) {
        RateLimiter::hit($throttleKey, 3600);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez utiliser une adresse email valide et permanente.'
            ], 422);
        }
        
        return back()->with('error', 'Veuillez utiliser une adresse email valide et permanente.')
            ->withInput();
    }
    
    // 6. Vérification des mots-clés de spam
    $spamKeywords = ['viagra', 'casino', 'poker', 'lottery', 'winner', 'prize', 'bitcoin', 'crypto', 'gagner', 'argent facile'];
    $messageLower = strtolower($cleanedMessage);
    $spamCount = 0;
    
    foreach ($spamKeywords as $keyword) {
        if (strpos($messageLower, $keyword) !== false) {
            $spamCount++;
        }
    }
    
    if ($spamCount > 2) {
        RateLimiter::hit($throttleKey, 3600);
        
        \Log::warning('Spam détecté dans formulaire de contact', [
            'ip' => $request->ip(),
            'email' => $cleanedEmail,
            'spam_keywords_count' => $spamCount
        ]);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Votre message contient des termes inappropriés.'
            ], 422);
        }
        
        return back()->with('error', 'Votre message contient des termes inappropriés.')->withInput();
    }
    
    // 7. Sauvegarde en base avec données nettoyées
    $contact = \App\Models\ContactMessage::create([
        'name' => $cleanedName,
        'email' => $cleanedEmail,
        'phone' => $cleanedPhone,
        'subject' => $cleanedSubject,  // Sujet par défaut
        'message' => $cleanedMessage,
        'ip_address' => $request->ip(),
        'user_agent' => substr($request->userAgent() ?? 'Unknown', 0, 500),
        'status' => 'pending',
        'submitted_at' => now(),
    ]);
    
    // 8. Incrémenter le compteur de tentatives
    RateLimiter::hit($throttleKey, 3600);
    
    // 9. Envoyer une notification par email
    $emailSent = false;
    try {
        $adminEmail = config('mail.admin_address', 'admin@barayoro.com');
        
        \Mail::send('emails.contact-notification', [
            'name' => $cleanedName,
            'email' => $cleanedEmail,
            'phone' => $cleanedPhone ?? 'Non fourni',
            'subject' => $cleanedSubject,
            'message' => $cleanedMessage,
            'contact_id' => $contact->id,
            'ip' => $request->ip(),
        ], function($mail) use ($adminEmail, $cleanedEmail, $cleanedName) {
            $mail->to($adminEmail)
                ->subject('📬 Nouveau message de contact - Barayoro')
                ->replyTo($cleanedEmail, $cleanedName)
                ->from(config('mail.from.address'), 'Barayoro Contact');
        });
        
        $emailSent = true;
        $contact->update(['email_sent_at' => now(), 'email_sent' => true]);
        
    } catch (\Exception $e) {
        \Log::error('Erreur envoi email contact: ' . $e->getMessage(), [
            'contact_id' => $contact->id,
            'email' => $cleanedEmail
        ]);
        $contact->update(['email_error' => $e->getMessage()]);
    }
    
    // 10. Journalisation
    \Log::channel('audit')->info('Message de contact reçu', [
        'contact_id' => $contact->id,
        'email' => substr($cleanedEmail, 0, 3) . '***@***',
        'ip' => $request->ip(),
        'email_sent' => $emailSent
    ]);
    
    // 11. Réponse
    $successMessage = 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.';
    
    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'contact_id' => $contact->id
        ]);
    }
    
    return redirect()->route('contact')->with('success', $successMessage);
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