<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Afficher la liste des projets
     */
    public function index(Request $request)
    {
        $query = Project::where('company_id', Auth::user()->company_id)
                        ->with(['client', 'department', 'manager']);

        // Filtrer par recherche
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filtrer par statut
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filtrer par priorité
        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        // Filtrer par client
        if ($request->has('client_id') && $request->client_id) {
            $query->where('client_id', $request->client_id);
        }

        // Trier
        $sortField = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        $projects = $query->paginate($request->get('per_page', 15));

        // Pour les filtres
        $clients = Client::where('company_id', Auth::user()->company_id)
                         ->where('status', 'active')
                         ->get();

        return view('projects.index', compact('projects', 'clients'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $clients = Client::where('company_id', Auth::user()->company_id)
                         ->where('status', 'active')
                         ->get();

        $departments = Department::where('company_id', Auth::user()->company_id)
                                 ->where('is_active', true)
                                 ->get();

        $managers = User::where('company_id', Auth::user()->company_id)
                        ->where('is_active', true)
                        ->get();

        return view('projects.create', compact('clients', 'departments', 'managers'));
    }

    /**
     * Enregistrer un nouveau projet
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'nullable|exists:clients,id',
            'department_id' => 'nullable|exists:departments,id',
            'project_manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:draft,planned,in_progress,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,critical',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'tags' => 'nullable|array',
        ]);

        $project = Project::create([
            'uuid' => Str::uuid(),
            'company_id' => Auth::user()->company_id,
            'name' => $request->name,
            'description' => $request->description,
            'client_id' => $request->client_id,
            'department_id' => $request->department_id,
            'project_manager_id' => $request->project_manager_id,
            'status' => $request->status,
            'priority' => $request->priority,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'budget' => $request->budget,
            'tags' => $request->tags,
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Projet créé avec succès.');
    }

    /**
     * Afficher les détails d'un projet
     */
    /**
 * Afficher les détails d'un projet
 */
public function show(Project $project)
{
    $this->checkCompanyAccess($project);

    $project->load(['client', 'department', 'manager', 'tasks']);

    $stats = [
        'total_tasks' => $project->tasks()->count(),
        'completed_tasks' => $project->tasks()->where('status', 'completed')->count(),
        'in_progress_tasks' => $project->tasks()->where('status', 'in_progress')->count(),
        'progress' => $project->progress,
    ];

    // Ajouter ces champs avec des valeurs par défaut si les tables n'existent pas
    try {
        $stats['total_expenses'] = $project->expenses()->sum('amount');
    } catch (\Exception $e) {
        $stats['total_expenses'] = 0;
    }

    try {
        $stats['total_time'] = $project->timeEntries()->sum('duration_minutes');
    } catch (\Exception $e) {
        $stats['total_time'] = 0;
    }

    return view('projects.show', compact('project', 'stats'));
}

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Project $project)
    {
        $this->checkCompanyAccess($project);

        $clients = Client::where('company_id', Auth::user()->company_id)
                         ->where('status', 'active')
                         ->get();

        $departments = Department::where('company_id', Auth::user()->company_id)
                                 ->where('is_active', true)
                                 ->get();

        $managers = User::where('company_id', Auth::user()->company_id)
                        ->where('is_active', true)
                        ->get();

        return view('projects.edit', compact('project', 'clients', 'departments', 'managers'));
    }

    /**
     * Mettre à jour un projet
     */
    public function update(Request $request, Project $project)
    {
        $this->checkCompanyAccess($project);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'nullable|exists:clients,id',
            'department_id' => 'nullable|exists:departments,id',
            'project_manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:draft,planned,in_progress,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,critical',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'progress' => 'nullable|integer|min:0|max:100',
            'tags' => 'nullable|array',
        ]);

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'client_id' => $request->client_id,
            'department_id' => $request->department_id,
            'project_manager_id' => $request->project_manager_id,
            'status' => $request->status,
            'priority' => $request->priority,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'budget' => $request->budget,
            'actual_cost' => $request->actual_cost,
            'progress' => $request->progress,
            'tags' => $request->tags,
        ]);

        // Si le projet est marqué comme complété
        if ($request->status === 'completed' && !$project->completed_at) {
            $project->update(['completed_at' => now()]);
        }

        return redirect()->route('projects.show', $project)
            ->with('success', 'Projet mis à jour avec succès.');
    }

    /**
     * Supprimer un projet (soft delete)
     */
    public function destroy(Project $project)
    {
        $this->checkCompanyAccess($project);

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Projet supprimé avec succès.');
    }

    /**
     * Restaurer un projet supprimé
     */
    public function restore($id)
    {
        $project = Project::withTrashed()->findOrFail($id);
        $this->checkCompanyAccess($project);

        $project->restore();

        return redirect()->route('projects.index')
            ->with('success', 'Projet restauré avec succès.');
    }

    /**
     * Mettre à jour la progression du projet
     */
    public function updateProgress(Project $project)
    {
        $this->checkCompanyAccess($project);

        $project->updateProgress();

        return redirect()->route('projects.show', $project)
            ->with('success', 'Progression mise à jour.');
    }

    /**
     * Exporter les projets en CSV
     */
    public function export()
    {
        $projects = Project::where('company_id', Auth::user()->company_id)->get();

        $filename = 'projets_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://temp', 'w+');

        fputcsv($handle, ['Code', 'Nom', 'Client', 'Statut', 'Priorité', 'Date début', 'Date fin', 'Progression', 'Budget']);

        foreach ($projects as $project) {
            fputcsv($handle, [
                $project->code,
                $project->name,
                $project->client->name ?? '-',
                $project->status,
                $project->priority,
                $project->start_date ? $project->start_date->format('d/m/Y') : '-',
                $project->due_date ? $project->due_date->format('d/m/Y') : '-',
                $project->progress . '%',
                number_format($project->budget, 0, ',', ' ') . ' FCFA',
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
     * Vérifier que le projet appartient à l'entreprise
     */
    private function checkCompanyAccess(Project $project)
    {
        if ($project->company_id !== Auth::user()->company_id) {
            abort(403, 'Accès non autorisé.');
        }
    }
}
