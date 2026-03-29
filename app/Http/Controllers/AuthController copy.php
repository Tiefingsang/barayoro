<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

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
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Mettre à jour la dernière connexion
            Auth::user()->update([
                'last_login_at' => now(),
                'last_activity_at' => now(),
            ]);

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
     * Traiter l'inscription d'une entreprise
     */
    public function register(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            // Informations entreprise
            'company_name' => 'required|string|max:255',
            'siret' => 'nullable|string|max:50|unique:companies,siret',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'subscription_plan' => 'required|in:trial,premium',

            // Informations administrateur
            'admin_name' => 'required|string|max:255',
            'admin_position' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted',
        ]);

        // 1. Création de l'entreprise
        $company = Company::create([
            'uuid' => (string) Str::uuid(),
            'name' => $request->company_name,
            'slug' => Str::slug($request->company_name . '-' . Str::random(6)),
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
            'siret' => $request->siret,
            'is_active' => true,
            'subscription_status' => $request->subscription_plan === 'trial' ? 'active' : 'pending',
            'subscription_started_at' => $request->subscription_plan === 'trial' ? now() : null,
            'subscription_expires_at' => $request->subscription_plan === 'trial' ? now()->addDays(30) : null,
            'max_users' => $request->subscription_plan === 'trial' ? 5 : 999,
            'settings' => [
                'currency' => 'XOF',
                'timezone' => 'Africa/Dakar',
                'language' => 'fr',
                'date_format' => 'd/m/Y',
            ],
        ]);

        // 2. Vérifier que les rôles existent, sinon les créer
        $this->ensureRolesExist();

        // 3. Création de l'utilisateur administrateur
        $user = User::create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $company->id,
            'name' => $request->admin_name,
            'username' => Str::slug($request->admin_name) . '-' . Str::random(4),
            'email' => $request->email,
            'position' => $request->admin_position ?? 'Administrateur',
            'password' => Hash::make($request->password),
            'is_active' => true,
            'email_verified_at' => now(),
            'timezone' => 'Africa/Dakar',
            'language' => 'fr',
            'preferences' => [
                'theme' => 'light',
                'notifications' => true,
                'dashboard_widgets' => ['tasks', 'stats', 'recent_activity'],
            ],
        ]);

        // 4. Assigner le rôle ADMIN à l'utilisateur
        $user->assignRole('admin');

        // 5. Créer automatiquement un département par défaut
        $this->createDefaultDepartment($company, $user);

        // 6. Créer des tâches de bienvenue
        $this->createWelcomeTasks($company, $user);

        // 7. Connecter l'utilisateur automatiquement
        Auth::login($user);

        // 8. Redirection vers le tableau de bord avec message de bienvenue
        return redirect()->route('dashboard')->with('success', 'Bienvenue sur Barayoro ! Votre entreprise a été créée avec succès. Vous êtes maintenant administrateur et pouvez ajouter des utilisateurs.');
    }

    /**
     * S'assurer que les rôles existent
     */
    private function ensureRolesExist()
    {
        $roles = ['admin', 'manager', 'employee'];

        foreach ($roles as $role) {
            if (!\Spatie\Permission\Models\Role::where('name', $role)->exists()) {
                \Spatie\Permission\Models\Role::create(['name' => $role]);
            }
        }

        // Créer les permissions si elles n'existent pas
        $permissions = [
            'view_users', 'create_users', 'edit_users', 'delete_users',
            'view_tasks', 'create_tasks', 'edit_tasks', 'delete_tasks', 'assign_tasks',
            'view_projects', 'create_projects', 'edit_projects', 'delete_projects',
            'view_clients', 'create_clients', 'edit_clients', 'delete_clients',
            'view_invoices', 'create_invoices', 'edit_invoices', 'delete_invoices', 'pay_invoices',
            'view_reports', 'export_reports',
            'manage_settings', 'manage_company',
        ];

        foreach ($permissions as $perm) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $perm]);
        }

        // Assigner les permissions aux rôles
        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $managerRole = \Spatie\Permission\Models\Role::findByName('manager');
        $employeeRole = \Spatie\Permission\Models\Role::findByName('employee');

        // Admin : toutes les permissions
        $adminRole->syncPermissions(\Spatie\Permission\Models\Permission::all());

        // Manager : permissions de gestion
        $managerRole->syncPermissions([
            'view_users', 'create_users', 'edit_users',
            'view_tasks', 'create_tasks', 'edit_tasks', 'assign_tasks',
            'view_projects', 'create_projects', 'edit_projects',
            'view_clients', 'create_clients', 'edit_clients',
            'view_invoices', 'create_invoices', 'edit_invoices',
            'view_reports', 'export_reports',
        ]);

        // Employee : accès limité
        $employeeRole->syncPermissions([
            'view_tasks', 'edit_tasks',
            'view_projects',
            'view_clients',
        ]);
    }

    /**
     * Créer un département par défaut
     */
    private function createDefaultDepartment($company, $user)
    {
        \App\Models\Department::create([
            'uuid' => Str::uuid(),
            'company_id' => $company->id,
            'name' => 'Général',
            'code' => 'GEN-' . strtoupper(Str::random(4)),
            'description' => 'Département par défaut',
            'manager_id' => $user->id,
            'is_active' => true,
        ]);
    }

    /**
     * Créer des tâches de bienvenue
     */
    private function createWelcomeTasks($company, $user)
    {
        $tasks = [
            [
                'title' => 'Bienvenue sur Barayoro !',
                'description' => 'Découvrez toutes les fonctionnalités de la plateforme',
                'priority' => 'high',
                'due_date' => now()->addDays(7),
            ],
            [
                'title' => 'Ajoutez vos premiers collaborateurs',
                'description' => 'Invitez vos collègues à rejoindre votre entreprise',
                'priority' => 'high',
                'due_date' => now()->addDays(14),
            ],
            [
                'title' => 'Créez votre premier projet',
                'description' => 'Commencez à organiser vos activités',
                'priority' => 'medium',
                'due_date' => now()->addDays(21),
            ],
        ];

        foreach ($tasks as $task) {
            \App\Models\Task::create([
                'uuid' => Str::uuid(),
                'company_id' => $company->id,
                'assigned_to' => $user->id,
                'created_by' => $user->id,
                'title' => $task['title'],
                'description' => $task['description'],
                'status' => 'pending',
                'priority' => $task['priority'],
                'due_date' => $task['due_date'],
                'sync_status' => 'synced',
            ]);
        }
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
