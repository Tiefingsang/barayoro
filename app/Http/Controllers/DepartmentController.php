<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    /**
     * Afficher la liste des départements
     */
    public function index(Request $request)
    {
        $query = Department::where('company_id', Auth::user()->company_id);

        // Filtrer par recherche
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        // Filtrer par statut
        if ($request->has('status') && $request->status) {
            $query->where('is_active', $request->status === 'active');
        }

        // Trier
        $sortField = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        $departments = $query->with('manager')->paginate($request->get('per_page', 15));

        return view('departments.index', compact('departments'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $managers = User::where('company_id', Auth::user()->company_id)
                        ->where('is_active', true)
                        ->get();

        return view('departments.create', compact('managers'));
    }

    /**
     * Enregistrer un nouveau département
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        // Vérifier que le manager appartient à l'entreprise
        if ($request->manager_id) {
            $manager = User::find($request->manager_id);
            if ($manager && $manager->company_id !== Auth::user()->company_id) {
                return back()->withErrors(['manager_id' => 'Le manager sélectionné n\'appartient pas à votre entreprise.']);
            }
        }

        $department = Department::create([
            'uuid' => Str::uuid(),
            'company_id' => Auth::user()->company_id,
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'manager_id' => $request->manager_id,
            'is_active' => $request->has('is_active'),
            'settings' => $request->settings ?? [],
        ]);

        return redirect()->route('departments.index')
            ->with('success', 'Département créé avec succès.');
    }

    /**
     * Afficher les détails d'un département
     */
    public function show(Department $department)
    {
        $this->checkCompanyAccess($department);

        $stats = [
            'total_users' => $department->users()->count(),
            'total_teams' => $department->teams()->count(),
            'total_projects' => $department->projects()->count(),
        ];

        return view('departments.show', compact('department', 'stats'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Department $department)
    {
        $this->checkCompanyAccess($department);

        $managers = User::where('company_id', Auth::user()->company_id)
                        ->where('is_active', true)
                        ->get();

        return view('departments.edit', compact('department', 'managers'));
    }

    /**
     * Mettre à jour un département
     */
    public function update(Request $request, Department $department)
    {
        $this->checkCompanyAccess($department);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        // Vérifier que le manager appartient à l'entreprise
        if ($request->manager_id) {
            $manager = User::find($request->manager_id);
            if ($manager && $manager->company_id !== Auth::user()->company_id) {
                return back()->withErrors(['manager_id' => 'Le manager sélectionné n\'appartient pas à votre entreprise.']);
            }
        }

        $department->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'manager_id' => $request->manager_id,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('departments.index')
            ->with('success', 'Département mis à jour avec succès.');
    }

    /**
     * Supprimer un département (soft delete)
     */
    public function destroy(Department $department)
    {
        $this->checkCompanyAccess($department);

        // Vérifier si le département a des utilisateurs
        if ($department->users()->count() > 0) {
            return redirect()->route('departments.index')
                ->with('error', 'Impossible de supprimer ce département car il contient des utilisateurs.');
        }

        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Département supprimé avec succès.');
    }

    /**
     * Restaurer un département supprimé
     */
    public function restore($id)
    {
        $department = Department::withTrashed()->findOrFail($id);
        $this->checkCompanyAccess($department);

        $department->restore();

        return redirect()->route('departments.index')
            ->with('success', 'Département restauré avec succès.');
    }

    /**
     * Activer/Désactiver un département
     */
    public function toggleStatus(Department $department)
    {
        $this->checkCompanyAccess($department);

        $department->update(['is_active' => !$department->is_active]);

        $status = $department->is_active ? 'activé' : 'désactivé';

        return redirect()->route('departments.index')
            ->with('success', "Département {$status} avec succès.");
    }

    /**
     * Exporter les départements en CSV
     */
    public function export()
    {
        $departments = Department::where('company_id', Auth::user()->company_id)->get();

        $filename = 'departements_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://temp', 'w+');

        // En-têtes CSV
        fputcsv($handle, ['Code', 'Nom', 'Description', 'Manager', 'Statut', 'Créé le']);

        // Données
        foreach ($departments as $department) {
            fputcsv($handle, [
                $department->code,
                $department->name,
                $department->description,
                $department->manager->name ?? '-',
                $department->is_active ? 'Actif' : 'Inactif',
                $department->created_at->format('d/m/Y'),
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Vérifier que le département appartient à l'entreprise
     */
    private function checkCompanyAccess(Department $department)
    {
        if ($department->company_id !== Auth::user()->company_id) {
            abort(403, 'Accès non autorisé.');
        }
    }
}
