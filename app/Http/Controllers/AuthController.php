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


use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traiter la tentative de connexion
     */
    public function login(Request $request)
    {
        //dd('rrrr');
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //dd('rrrr');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Mettre à jour la dernière connexion
            Auth::user()->update(['last_login_at' => now()]);

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Traiter l'inscription
     */
    public function register(Request $request)
    {
        // Validation
        $request->validate([
            'company_name' => 'required|string|max:255',
            'admin_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'subscription_plan' => 'required|in:trial,premium',
            'terms' => 'accepted',
        ]);

        // Création de l'entreprise
        $company = Company::create([
            'uuid' => Str::uuid(),
            'name' => $request->company_name,
            'slug' => Str::slug($request->company_name . '-' . Str::random(6)),
            'email' => $request->email,
            'is_active' => true,
            'subscription_status' => $request->subscription_plan === 'trial' ? 'active' : 'pending',
            'subscription_expires_at' => $request->subscription_plan === 'trial' ? now()->addDays(30) : null,
            'max_users' => $request->subscription_plan === 'trial' ? 5 : 999,
        ]);

        // Création de l'utilisateur admin
        $user = User::create([
            'uuid' => Str::uuid(),
            'company_id' => $company->id,
            'name' => $request->admin_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        $user->assignRole('admin');
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Compte créé avec succès !');
    }


    /**
     * Afficher le formulaire de mot de passe oublié
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envoyer le lien de réinitialisation
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Supprimer l'ancien token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Récupérer le token créé (non haché pour le log)
        $tokenRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        // Le token est haché dans la base, mais on peut voir qu'il a été créé
        \Log::info('Token créé pour: ' . $request->email);
        \Log::info('Token hash dans DB: ' . ($tokenRecord ? $tokenRecord->token : 'non créé'));

        if ($status === Password::RESET_LINK_SENT) {
            return redirect()->route('password.confirmation', ['email' => $request->email])
                            ->with('status', 'Un lien de réinitialisation vous a été envoyé par email.');
        }

        return back()->withErrors(['email' => 'Une erreur est survenue. Veuillez réessayer.']);
    }


    public function showConfirmation(Request $request)
    {
        $email = $request->email;
        return view('auth.forgot-password-confirmation', compact('email'));
    }
    /**
     * Afficher le formulaire de réinitialisation
     */
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword(Request $request)
{
    \Log::info('Tentative de réinitialisation', [
        'email' => $request->email,
        'token' => $request->token
    ]);

    // Vérifier si le token existe dans password_reset_tokens
    $reset = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->where('token', $request->token)
        ->first();

    \Log::info('Token trouvé dans DB: ' . ($reset ? 'Oui' : 'Non'));

    $request->validate([
        'token' => 'required',
        'email' => 'required|email|exists:users,email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            \Log::info('Réinitialisation pour: ' . $user->email);
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
        }
    );

    \Log::info('Statut de réinitialisation: ' . $status);

    if ($status === Password::PASSWORD_RESET) {
        // Supprimer le token après utilisation
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Votre mot de passe a été réinitialisé avec succès. Connectez-vous avec votre nouveau mot de passe.');
    }

    return back()->withErrors(['email' => 'Une erreur est survenue. Veuillez réessayer.']);
}

    /**
     * Déconnecter l'utilisateur
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
