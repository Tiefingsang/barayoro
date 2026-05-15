<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Nombre maximum de tentatives de connexion
     */
    protected $maxLoginAttempts = 5;
    
    /**
     * Temps de blocage en minutes après trop de tentatives
     */
    protected $decayMinutes = 15;

    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traiter la tentative de connexion avec protection anti-force brute
     */
    public function login(LoginRequest $request)
    {
        // Vérification du rate limiting
        $this->checkTooManyFailedAttempts($request);
        
        $credentials = $request->validated();
        
        // Nettoyer les entrées
        $email = filter_var($credentials['email'], FILTER_SANITIZE_EMAIL);
        
        if (Auth::attempt(['email' => $email, 'password' => $credentials['password']], $request->boolean('remember'))) {
            // Réinitialiser les tentatives
            RateLimiter::clear($this->throttleKey($request));
            
            $request->session()->regenerate();
            
            // Journaliser la connexion réussie
            Log::channel('audit')->info('Connexion réussie', [
                'user_id' => Auth::id(),
                'email' => $email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            // Mettre à jour la dernière connexion
            Auth::user()->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
                'login_count' => DB::raw('login_count + 1')
            ]);
            
            return redirect()->intended('/dashboard');
        }
        
        // Journaliser la tentative échouée
        Log::channel('audit')->warning('Tentative de connexion échouée', [
            'email' => $email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        // Incrémenter le compteur de tentatives
        RateLimiter::hit($this->throttleKey($request), $this->decayMinutes * 60);
        
        throw ValidationException::withMessages([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ]);
    }
    
    /**
     * Vérifier les tentatives excessives
     */
    protected function checkTooManyFailedAttempts(Request $request)
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), $this->maxLoginAttempts)) {
            return;
        }
        
        $seconds = RateLimiter::availableIn($this->throttleKey($request));
        
        Log::channel('audit')->warning('Trop de tentatives de connexion', [
            'ip' => $request->ip(),
            'email' => $request->email
        ]);
        
        throw ValidationException::withMessages([
            'email' => "Trop de tentatives de connexion. Veuillez réessayer dans {$seconds} secondes.",
        ]);
    }
    
    /**
     * Générer la clé de throttle
     */
    protected function throttleKey(Request $request): string
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Traiter l'inscription avec sécurité renforcée
     */
    public function register(RegisterRequest $request)
    {
        // Vérification des inscriptions abusives
        $this->checkAbusiveRegistration($request);
        
        // Début de transaction
        DB::beginTransaction();
        
        try {
            // Nettoyage des données
            $cleanData = $this->sanitizeRegistrationData($request);
            
            // Création de l'entreprise
            $company = Company::create([
                'uuid' => (string) Str::uuid(),
                'name' => $cleanData['company_name'],
                'slug' => Str::slug($cleanData['company_name'] . '-' . Str::random(8)),
                'email' => $cleanData['email'],
                'phone' => $cleanData['phone'] ?? null,
                'country' => $cleanData['country'] ?? 'ML',
                'siret' => $this->encryptSensitiveData($cleanData['siret'] ?? null),
                'business_type' => $cleanData['business_type'] ?? null,
                'address' => $cleanData['address'] ?? null,
                'is_active' => true,
                'subscription_status' => 'trial',
                'subscription_expires_at' => null,
                'max_users' => 5,
                'trial_ends_at' => now()->addDays(30),
                'subscription_started_at' => now(),
                'subscription_price' => 0,
                'registration_ip' => $request->ip(),
                'registration_user_agent' => $request->userAgent(),
            ]);
            
            // Création de l'utilisateur admin
            $user = User::create([
                'uuid' => (string) Str::uuid(),
                'company_id' => $company->id,
                'name' => $cleanData['admin_name'],
                'email' => $cleanData['email'],
                'position' => $cleanData['admin_position'] ?? 'Administrateur',
                'password' => Hash::make($cleanData['password'], ['rounds' => 12]),
                'is_active' => true,
                'email_verified_at' => null, // Nécessite vérification email
                'registration_ip' => $request->ip(),
                'last_login_ip' => $request->ip(),
            ]);
            
            // Attribution du rôle
            $user->assignRole('admin');
            
            // Journalisation
            Log::channel('audit')->info('Nouvelle entreprise inscrite', [
                'company_id' => $company->id,
                'company_name' => $company->name,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'ip' => $request->ip(),
                'subscription_plan' => $request->subscription_plan
            ]);
            
            DB::commit();
            
            // Envoi d'email de bienvenue (à implémenter)
            // Mail::to($user->email)->send(new WelcomeMail($user, $company));
            
            // Connecter l'utilisateur
            Auth::login($user);
            
            return redirect()->route('dashboard')
                ->with('success', 'Compte créé avec succès ! Vous bénéficiez de 30 jours d\'essai gratuit.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::channel('audit')->error('Erreur lors de l\'inscription', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
                'email' => $request->email
            ]);
            
            return back()->with('error', 'Une erreur technique est survenue. Veuillez réessayer plus tard.')
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }
    
    /**
     * Vérifier les inscriptions abusives
     */
    protected function checkAbusiveRegistration(Request $request)
    {
        $key = 'register:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            Log::channel('audit')->warning('Trop de tentatives d\'inscription', ['ip' => $request->ip()]);
            throw ValidationException::withMessages([
                'email' => "Trop de tentatives d'inscription. Veuillez réessayer dans {$seconds} secondes."
            ]);
        }
        
        RateLimiter::hit($key, 3600); // 3 tentatives par heure
    }
    
    /**
     * Nettoyer les données d'inscription
     */
    protected function sanitizeRegistrationData(Request $request): array
    {
        return [
            'company_name' => htmlspecialchars(trim($request->company_name), ENT_QUOTES, 'UTF-8'),
            'admin_name' => htmlspecialchars(trim($request->admin_name), ENT_QUOTES, 'UTF-8'),
            'admin_position' => $request->admin_position ? htmlspecialchars(trim($request->admin_position), ENT_QUOTES, 'UTF-8') : null,
            'email' => filter_var(trim($request->email), FILTER_SANITIZE_EMAIL),
            'password' => $request->password,
            'subscription_plan' => $request->subscription_plan,
            'phone' => $request->phone ? preg_replace('/[^0-9+]/', '', $request->phone) : null,
            'country' => $request->country,
            'siret' => $request->siret ? preg_replace('/[^0-9]/', '', $request->siret) : null,
            'business_type' => $request->business_type,
            'address' => $request->address ? htmlspecialchars(trim($request->address), ENT_QUOTES, 'UTF-8') : null,
        ];
    }
    
    /**
     * Chiffrer les données sensibles
     */
    protected function encryptSensitiveData($data)
    {
        if (empty($data)) {
            return null;
        }
        
        try {
            return encrypt($data);
        } catch (\Exception $e) {
            Log::error('Erreur chiffrement', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Afficher le formulaire de mot de passe oublié
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envoyer le lien de réinitialisation avec sécurité
     */
    public function sendResetLink(ForgotPasswordRequest $request)
    {
        // Vérification du rate limiting
        $key = 'reset-password:' . $request->ip() . ':' . $request->email;
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Trop de demandes. Veuillez réessayer dans {$seconds} secondes."
            ]);
        }
        
        $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
        
        // Vérifier si l'utilisateur existe
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            // Ne pas révéler que l'email n'existe pas (sécurité)
            RateLimiter::hit($key, 300);
            return redirect()->route('password.confirmation', ['email' => $email])
                ->with('status', 'Si votre email est enregistré, vous recevrez un lien de réinitialisation.');
        }
        
        // Supprimer l'ancien token
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        
        $status = Password::sendResetLink(
            ['email' => $email]
        );
        
        RateLimiter::hit($key, 300);
        
        Log::channel('audit')->info('Demande de réinitialisation mot de passe', [
            'email' => $email,
            'ip' => $request->ip(),
            'status' => $status
        ]);
        
        return redirect()->route('password.confirmation', ['email' => $email])
            ->with('status', 'Si votre email est enregistré, vous recevrez un lien de réinitialisation.');
    }

    /**
     * Afficher la page de confirmation
     */
    public function showConfirmation(Request $request)
    {
        $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
        return view('auth.forgot-password-confirmation', compact('email'));
    }
    
    /**
     * Afficher le formulaire de réinitialisation
     */
    public function showResetForm($token)
    {
        // Nettoyer le token
        $token = preg_replace('/[^a-zA-Z0-9]/', '', $token);
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Réinitialiser le mot de passe avec sécurité renforcée
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        // Vérification du rate limiting
        $key = 'reset-password-execute:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Trop de tentatives. Veuillez réessayer dans {$seconds} secondes."
            ]);
        }
        
        $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
        $token = preg_replace('/[^a-zA-Z0-9]/', '', $request->token);
        
        // Vérifier si le token existe
        $reset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();
            
        if (!$reset || !Hash::check($token, $reset->token)) {
            Log::channel('audit')->warning('Token invalide pour réinitialisation', [
                'email' => $email,
                'ip' => $request->ip()
            ]);
            
            return back()->withErrors(['email' => 'Le lien de réinitialisation est invalide ou a expiré.']);
        }
        
        $status = Password::reset(
            [
                'email' => $email,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
                'token' => $token
            ],
            function ($user, $password) use ($request) {
                // Mise à jour sécurisée du mot de passe
                $user->forceFill([
                    'password' => Hash::make($password, ['rounds' => 12]),
                    'remember_token' => Str::random(60),
                    'password_changed_at' => now(),
                    'password_changed_ip' => $request->ip()
                ])->save();
                
                // Invalider toutes les autres sessions
                if (method_exists($user, 'sessions')) {
                    $user->sessions()->delete();
                }
                
                event(new PasswordReset($user));
                
                Log::channel('audit')->info('Mot de passe réinitialisé avec succès', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip()
                ]);
            }
        );
        
        RateLimiter::hit($key, 300);
        
        if ($status === Password::PASSWORD_RESET) {
            // Supprimer le token après utilisation
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            
            // Déconnecter toutes les sessions actives (optionnel)
            // Auth::logout();
            
            return redirect()->route('login')
                ->with('status', 'Votre mot de passe a été réinitialisé avec succès. Connectez-vous avec votre nouveau mot de passe.');
        }
        
        return back()->withErrors(['email' => 'Une erreur est survenue. Veuillez réessayer.']);
    }

    /**
     * Déconnecter l'utilisateur
     */
    public function logout(Request $request)
    {
        // Journaliser la déconnexion
        if (Auth::check()) {
            Log::channel('audit')->info('Déconnexion', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'ip' => $request->ip()
            ]);
        }
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('status', 'Vous avez été déconnecté avec succès.');
    }
}