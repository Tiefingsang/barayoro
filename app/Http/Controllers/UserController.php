<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        //
    }

    /**
     * Liste des utilisateurs de l'entreprise
     */
    public function index()
    {
        $users = User::where('company_id', Auth::user()->company_id)
                     ->with('roles')
                     ->orderBy('created_at', 'desc')
                     ->paginate(15);

        // Transformer les données pour qu'elles soient compatibles avec la vue Alpine.js
        $usersData = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'position' => $user->position,
                'avatar_url' => $user->avatar_url,
                'role_name' => $user->roles->first()->name ?? 'employee',
                'is_active' => $user->is_active,
                'status' => $user->is_active ? 'active' : 'inactive',
                'company' => [
                    'name' => $user->company->name ?? null
                ],
                'created_at' => $user->created_at->format('d/m/Y'),
                'checked' => false
            ];
        });

        $roles = Role::all();

        return view('users.index', compact('users', 'usersData', 'roles'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Créer un nouvel utilisateur
     */
   /**
 * Créer un nouvel utilisateur
 */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create_users')) {
            abort(403, 'Vous n\'avez pas les droits pour créer des utilisateurs.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:active,inactive',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            // Le mot de passe est défini par défaut, pas besoin de validation
        ]);

        // Vérifier le nombre max d'utilisateurs
        $company = Auth::user()->company;
        $currentUsersCount = User::where('company_id', $company->id)->count();

        if ($currentUsersCount >= $company->max_users && !$company->unlimited_users) {
            return redirect()->back()
                ->with('error', 'Vous avez atteint le nombre maximum d\'utilisateurs autorisés pour votre abonnement.')
                ->withInput();
        }

        // Gérer l'avatar
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Créer l'utilisateur avec mot de passe par défaut
        $user = User::create([
            'uuid' => Str::uuid(),
            'company_id' => Auth::user()->company_id,
            'name' => $request->name,
            'username' => Str::slug($request->name) . '-' . Str::random(4),
            'email' => $request->email,
            'position' => $request->position,
            'phone' => $request->phone,
            'country' => $request->country,
            'avatar' => $avatarPath,
            'password' => Hash::make('12345678'), // Mot de passe par défaut
            'is_active' => $request->status === 'active',
        ]);

        $user->assignRole($request->role);

        // Log de création
        \Log::info('Nouvel utilisateur créé', [
            'email' => $user->email,
            'company' => $user->company->name,
            'created_by' => Auth::user()->email,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès. Le mot de passe par défaut est : 12345678');
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    /**
 * Afficher les détails d'un utilisateur
 */
/**
 * Afficher les détails d'un utilisateur
 */
public function show(User $user)
{
    if (!auth()->user()->can('view_users')) {
        abort(403, 'Vous n\'avez pas les droits pour voir les utilisateurs.');
    }

    $this->checkCompanyAccess($user);

    $userRole = $user->roles->first()->name ?? 'employee';

    // Compter les tâches et projets associés à l'utilisateur
    // Utiliser la relation directe si elle existe, sinon 0
    try {
        $user->tasks_count = $user->assignedTasks()->count();
    } catch (\Exception $e) {
        $user->tasks_count = 0;
    }

    try {
        $user->projects_count = $user->projects()->count();
    } catch (\Exception $e) {
        $user->projects_count = 0;
    }

    try {
        $user->time_entries_count = $user->timeEntries()->count();
    } catch (\Exception $e) {
        $user->time_entries_count = 0;
    }

    // Récupérer les activités récentes (optionnel)
    try {
        $recentActivities = $user->activityLogs()->latest()->limit(5)->get();
    } catch (\Exception $e) {
        $recentActivities = collect([]);
    }

    return view('users.show', compact('user', 'userRole', 'recentActivities'));
}


    /**
     * Formulaire d'édition
     */
   public function edit(User $user)
{
    // Débogage temporaire
    \Log::info('Edit user attempt', [
        'user_id' => $user->id,
        'user_company' => $user->company_id,
        'auth_company' => Auth::user()->company_id,
        'auth_user' => Auth::user()->id,
        'can_edit' => Auth::user()->can('edit_users')
    ]);

    $this->checkCompanyAccess($user);

    if (!Auth::user()->can('edit_users')) {
        abort(403, 'Vous n\'avez pas les droits pour modifier des utilisateurs.');
    }

    $roles = Role::all();
    $userRole = $user->roles->first()->name ?? 'employee';

    return view('users.edit', compact('user', 'roles', 'userRole'));
}


    /**
 * Mettre à jour un utilisateur
 */
public function update(Request $request, User $user)
{


    $this->checkCompanyAccess($user);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20',
        'position' => 'nullable|string|max:255',
        'employee_id' => 'nullable|string|max:50',
        'hire_date' => 'nullable|date',
        'employment_type' => 'nullable|in:full_time,part_time,contract,intern',
        'hourly_rate' => 'nullable|numeric|min:0',
        'role' => 'required|exists:roles,name',
        'password' => 'nullable|string|min:8|confirmed',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
    ]);

    // Gérer l'avatar
    if ($request->hasFile('avatar')) {
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $avatarPath;
    }

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'position' => $request->position,
        'employee_id' => $request->employee_id,
        'hire_date' => $request->hire_date,
        'employment_type' => $request->employment_type,

        'is_active' => $request->status === 'active',
        'email_verified_at' => $request->has('email_verified') ? now() : null,
        'two_factor_enabled' => $request->has('two_factor_enabled'),
    ]);

    if ($request->filled('password')) {
        $user->update(['password' => Hash::make($request->password)]);
    }

    $user->syncRoles([$request->role]);

    return redirect()->route('users.index')
                     ->with('success', 'Utilisateur mis à jour avec succès.');
}

    /**
     * Désactiver un utilisateur
     */
    public function destroy(User $user)
    {
        $this->checkCompanyAccess($user);

        // Empêcher la suppression de son propre compte
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                             ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->update(['is_active' => false]);
        // Ou suppression définitive
        // $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'Utilisateur désactivé avec succès.');
    }

    /**
     * Activer un utilisateur
     */
    public function activate(User $user)
    {
        $this->checkCompanyAccess($user);

        $user->update(['is_active' => true]);

        return redirect()->route('users.index')
                         ->with('success', 'Utilisateur activé avec succès.');
    }

    /**
     * Vérifier que l'utilisateur appartient bien à l'entreprise
     */
    private function checkCompanyAccess(User $user)
    {
        if ($user->company_id !== Auth::user()->company_id) {
            abort(403, 'Accès non autorisé.');
        }
    }
}
