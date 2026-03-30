<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    /**
     * Afficher la liste des tâches
     */
    public function index(Request $request)
    {
        $query = Task::where('company_id', Auth::user()->company_id)
                     ->with(['project', 'assignee', 'creator']);

        // Filtrer par recherche
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
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

        // Filtrer par projet
        if ($request->has('project_id') && $request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        // Filtrer par assigné à
        if ($request->has('assigned_to') && $request->assigned_to) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Trier
        $sortField = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        $tasks = $query->paginate($request->get('per_page', 15));

        // Pour les filtres
        $projects = Project::where('company_id', Auth::user()->company_id)->get();
        $users = User::where('company_id', Auth::user()->company_id)
                     ->where('is_active', true)
                     ->get();

        return view('tasks.index', compact('tasks', 'projects', 'users'));
    }

    /**
     * Afficher le formulaire de création
/**
 * Afficher le formulaire de création
 */
public function create(Request $request)
{
    $projects = Project::where('company_id', Auth::user()->company_id)->get();
    $departments = Department::where('company_id', Auth::user()->company_id)
                             ->where('is_active', true)
                             ->get();
    $users = User::where('company_id', Auth::user()->company_id)
                 ->where('is_active', true)
                 ->get();

    $selectedProject = null;
    if ($request->has('project_id')) {
        $selectedProject = Project::find($request->project_id);
    }

    return view('tasks.create', compact('projects', 'departments', 'users', 'selectedProject'));
}

    /**
     * Enregistrer une nouvelle tâche
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'department_id' => 'nullable|exists:departments,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,in_progress,review,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'estimated_hours' => 'nullable|integer|min:1|max:168',
        ]);

        $task = Task::create([
            'uuid' => Str::uuid(),
            'company_id' => Auth::user()->company_id,
            'title' => $request->title,
            'description' => $request->description,
            'project_id' => $request->project_id,
            'department_id' => $request->department_id,
            'assigned_to' => $request->assigned_to,
            'created_by' => Auth::id(),
            'status' => $request->status,
            'priority' => $request->priority,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'estimated_hours' => $request->estimated_hours,
        ]);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Tâche créée avec succès.');
    }

    /**
     * Afficher les détails d'une tâche
     */
    public function show(Task $task)
    {
        $this->checkCompanyAccess($task);

        $task->load(['project', 'department', 'assignee', 'creator', 'parent', 'children', 'comments']);

        $subtasks = $task->children;

        return view('tasks.show', compact('task', 'subtasks'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Task $task)
    {
        $this->checkCompanyAccess($task);

        $projects = Project::where('company_id', Auth::user()->company_id)->get();
        $departments = Department::where('company_id', Auth::user()->company_id)
                                 ->where('is_active', true)
                                 ->get();
        $users = User::where('company_id', Auth::user()->company_id)
                     ->where('is_active', true)
                     ->get();

        return view('tasks.edit', compact('task', 'projects', 'departments', 'users'));
    }

    /**
     * Mettre à jour une tâche
     */
    public function update(Request $request, Task $task)
    {
        $this->checkCompanyAccess($task);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'department_id' => 'nullable|exists:departments,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,in_progress,review,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'estimated_hours' => 'nullable|integer|min:1|max:168',
            'actual_hours' => 'nullable|integer|min:0',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'project_id' => $request->project_id,
            'department_id' => $request->department_id,
            'assigned_to' => $request->assigned_to,
            'status' => $request->status,
            'priority' => $request->priority,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'estimated_hours' => $request->estimated_hours,
            'actual_hours' => $request->actual_hours,
            'progress' => $request->progress,
        ]);

        // Si la tâche est marquée comme terminée
        if ($request->status === 'completed' && !$task->completed_at) {
            $task->update(['completed_at' => now()]);
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Tâche mise à jour avec succès.');
    }

    /**
     * Supprimer une tâche (soft delete)
     */
    public function destroy(Task $task)
    {
        $this->checkCompanyAccess($task);

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Tâche supprimée avec succès.');
    }

    /**
     * Restaurer une tâche supprimée
     */
    public function restore($id)
    {
        $task = Task::withTrashed()->findOrFail($id);
        $this->checkCompanyAccess($task);

        $task->restore();

        return redirect()->route('tasks.index')
            ->with('success', 'Tâche restaurée avec succès.');
    }

    /**
     * Mettre à jour le statut d'une tâche
     */
    public function updateStatus(Request $request, Task $task)
    {
        $this->checkCompanyAccess($task);

        $request->validate([
            'status' => 'required|in:pending,in_progress,review,completed,cancelled',
        ]);

        if ($request->status === 'completed') {
            $task->markAsCompleted();
        } elseif ($request->status === 'in_progress') {
            $task->markAsInProgress();
        } else {
            $task->update(['status' => $request->status]);
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Statut de la tâche mis à jour.');
    }

    /**
     * Exporter les tâches en CSV
     */
    public function export()
    {
        $tasks = Task::where('company_id', Auth::user()->company_id)->get();

        $filename = 'taches_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://temp', 'w+');

        fputcsv($handle, ['Code', 'Titre', 'Projet', 'Assigné à', 'Statut', 'Priorité', 'Échéance', 'Progression']);

        foreach ($tasks as $task) {
            fputcsv($handle, [
                $task->code,
                $task->title,
                $task->project->name ?? '-',
                $task->assignee->name ?? '-',
                $task->status,
                $task->priority,
                $task->due_date ? $task->due_date->format('d/m/Y') : '-',
                $task->progress . '%',
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
     * Vérifier que la tâche appartient à l'entreprise
     */
    private function checkCompanyAccess(Task $task)
    {
        if ($task->company_id !== Auth::user()->company_id) {
            abort(403, 'Accès non autorisé.');
        }
    }
}
