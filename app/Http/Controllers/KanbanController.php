<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KanbanController extends Controller
{
    /**
     * Afficher le tableau Kanban
     */
    public function index(Request $request)
    {
        $companyId = Auth::user()->company_id;

        // Récupérer le projet si spécifié
        $projectId = $request->get('project_id');

        $query = Task::where('company_id', $companyId);

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        $tasks = $query->get();

        // Organiser les tâches par statut
        $columns = [
            'todo' => [
                'title' => 'À faire',
                'color' => 'gray',
                'tasks' => $tasks->where('status', 'todo')
            ],
            'in_progress' => [
                'title' => 'En cours',
                'color' => 'blue',
                'tasks' => $tasks->where('status', 'in_progress')
            ],
            'review' => [
                'title' => 'En relecture',
                'color' => 'yellow',
                'tasks' => $tasks->where('status', 'review')
            ],
            'completed' => [
                'title' => 'Terminé',
                'color' => 'green',
                'tasks' => $tasks->where('status', 'completed')
            ]
        ];

        // Récupérer les projets pour le filtre
        $projects = Project::where('company_id', $companyId)
            ->where('status', 'in_progress')
            ->select('id', 'name')
            ->get();

        return view('kanban.index', compact('columns', 'projects', 'projectId'));
    }

    /**
     * Mettre à jour le statut d'une tâche (drag & drop)
     */
    public function updateTaskStatus(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'status' => 'required|in:todo,in_progress,review,completed'
        ]);

        $task = Task::where('company_id', Auth::user()->company_id)
            ->findOrFail($request->task_id);

        $oldStatus = $task->status;
        $task->status = $request->status;
        $task->save();

        // Enregistrer l'historique
        $task->comments()->create([
            'user_id' => Auth::id(),
            'content' => "Statut changé de {$oldStatus} à {$request->status}",
            'type' => 'system'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour avec succès',
            'task' => $task
        ]);
    }

    /**
     * Mettre à jour l'ordre des tâches
     */
    public function updateTaskOrder(Request $request)
    {
        $request->validate([
            'tasks' => 'required|array',
            'tasks.*.id' => 'required|exists:tasks,id',
            'tasks.*.order' => 'required|integer'
        ]);

        foreach ($request->tasks as $taskData) {
            Task::where('company_id', Auth::user()->company_id)
                ->where('id', $taskData['id'])
                ->update(['order' => $taskData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ordre mis à jour'
        ]);
    }

    /**
     * Ajouter une tâche rapide depuis le Kanban
     */
    public function quickTask(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:todo,in_progress,review,completed',
            'project_id' => 'nullable|exists:projects,id'
        ]);

        $task = Task::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'company_id' => Auth::user()->company_id,
            'project_id' => $request->project_id,
            'title' => $request->title,
            'status' => $request->status,
            'priority' => 'medium',
            'created_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tâche créée avec succès',
            'task' => $task->load('assignee')
        ]);
    }

    /**
     * Récupérer les tâches pour le Kanban (AJAX)
     */
    public function getTasks(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $query = Task::where('company_id', $companyId);

        if ($request->has('project_id') && $request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        $tasks = $query->orderBy('order')->get();

        return response()->json([
            'success' => true,
            'tasks' => $tasks->groupBy('status')
        ]);
    }
}
