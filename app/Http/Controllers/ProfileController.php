<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Afficher le formulaire d'édition du profil.
     */
    public function edit()
    {
        $user = Auth::user();
        
        // Récupérer les statistiques pour le dashboard du profil
        // Seulement les relations qui existent vraiment
        $stats = [
            'total_projects' => $user->projects()->count(), // Cette relation existe via project_user
            'total_tasks_created' => $user->createdTasks()->count(), // Relation createdTasks existe
            'total_tasks_assigned' => $user->assignedTasks()->count(), // Relation assignedTasks existe
        ];
        
        // Récupérer les activités récentes (si la table existe)
        $recentActivities = [];
        try {
            if ($user->activityLogs()) {
                $recentActivities = $user->activityLogs()
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
            }
        } catch (\Exception $e) {
            // Table activity_logs n'existe pas encore
            $recentActivities = collect();
        }
        
        return view('profile.edit', compact('user', 'stats', 'recentActivities'));
    }

    /**
     * Mettre à jour le profil utilisateur.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'position' => ['nullable', 'string', 'max:255'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'hire_date' => ['nullable', 'date'],
            'employment_type' => ['nullable', 'string', 'in:full_time,part_time,contract,intern'],
            'timezone' => ['nullable', 'string', 'timezone'],
            'language' => ['nullable', 'string', 'in:fr,en,es,de,it'],
            'theme' => ['nullable', 'string', 'in:light,dark,system'],
        ]);

        $user->update($validated);

        return redirect()->route('profile.edit')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Mettre à jour le mot de passe.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now(),
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Mot de passe mis à jour avec succès.');
    }

    /**
     * Mettre à jour l'avatar.
     */

// app/Http/Controllers/ProfileController.php

/**
 * Mettre à jour l'avatar.
 */
public function updateAvatar(Request $request)
{
    try {
        $user = Auth::user();

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        // Récupérer le vrai chemin stocké en base, sans accessseur
        $oldAvatar = $user->getRawOriginal('avatar');

        // Supprimer l'ancien avatar
        if ($oldAvatar && Storage::disk('public')->exists($oldAvatar)) {
            Storage::disk('public')->delete($oldAvatar);
        }

        // Stocker le nouvel avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        // Enregistrer seulement le chemin en base
        $user->forceFill([
            'avatar' => $path,
        ])->save();

        return response()->json([
            'success' => true,
            'avatar_url' => asset('storage/' . $path),
            'message' => 'Avatar mis à jour avec succès.',
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l’upload : ' . $e->getMessage(),
        ], 500);
    }
}

    /**
     * Supprimer l'avatar.
     */
    public function deleteAvatar(Request $request)
{
    $user = Auth::user();

    $oldAvatar = $user->getRawOriginal('avatar');

    if ($oldAvatar && Storage::disk('public')->exists($oldAvatar)) {
        Storage::disk('public')->delete($oldAvatar);
    }

    $user->forceFill([
        'avatar' => null,
    ])->save();

    return response()->json([
        'success' => true,
        'avatar_url' => $user->avatar_url,
        'message' => 'Avatar supprimé avec succès.',
    ]);
}

    /**
     * Mettre à jour les préférences utilisateur.
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'notifications_email' => ['boolean'],
            'notifications_push' => ['boolean'],
            'notifications_sound' => ['boolean'],
            'dashboard_layout' => ['string', 'in:grid,list,compact'],
            'items_per_page' => ['integer', 'min:10', 'max:100'],
        ]);

        $preferences = array_merge($user->preferences ?? [], $validated);
        $user->update(['preferences' => $preferences]);

        return redirect()->route('profile.edit')
            ->with('success', 'Préférences mises à jour avec succès.');
    }
}