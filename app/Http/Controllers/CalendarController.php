<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * Afficher le calendrier
     */
    public function index(Request $request)
    {
        $companyId = Auth::user()->company_id;

        // Récupérer les tâches avec échéances
        $tasks = Task::where('company_id', $companyId)
            ->whereNotNull('due_date')
            ->select('id', 'title', 'due_date', 'status', 'priority')
            ->get()
            ->map(function($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'start' => $task->due_date->format('Y-m-d'),
                    'end' => $task->due_date->format('Y-m-d'),
                    'type' => 'task',
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'url' => route('tasks.show', $task->id),
                    'color' => $this->getTaskColor($task->status, $task->priority)
                ];
            });

        // Récupérer les projets avec dates
        $projects = Project::where('company_id', $companyId)
            ->whereNotNull('start_date')
            ->select('id', 'name', 'start_date', 'due_date', 'status')
            ->get()
            ->map(function($project) {
                $events = [];

                if ($project->start_date) {
                    $events[] = [
                        'id' => $project->id . '_start',
                        'title' => 'Début: ' . $project->name,
                        'start' => $project->start_date->format('Y-m-d'),
                        'type' => 'project_start',
                        'status' => $project->status,
                        'url' => route('projects.show', $project->id),
                        'color' => '#10B981'
                    ];
                }

                if ($project->due_date) {
                    $events[] = [
                        'id' => $project->id . '_end',
                        'title' => 'Fin: ' . $project->name,
                        'start' => $project->due_date->format('Y-m-d'),
                        'type' => 'project_end',
                        'status' => $project->status,
                        'url' => route('projects.show', $project->id),
                        'color' => '#EF4444'
                    ];
                }

                return $events;
            })->flatten(1);

        // Récupérer les factures avec échéances
        $invoices = Invoice::where('company_id', $companyId)
            ->whereNotNull('due_date')
            ->where('status', 'pending')
            ->select('id', 'invoice_number', 'due_date', 'total', 'client_id')
            ->with('client')
            ->get()
            ->map(function($invoice) {
                return [
                    'id' => $invoice->id,
                    'title' => 'Facture: ' . $invoice->invoice_number . ' - ' . number_format($invoice->total, 0, ',', ' ') . ' FCFA',
                    'start' => $invoice->due_date->format('Y-m-d'),
                    'end' => $invoice->due_date->format('Y-m-d'),
                    'type' => 'invoice',
                    'client' => $invoice->client->name ?? 'Client',
                    'url' => route('invoices.show', $invoice->id),
                    'color' => '#F59E0B'
                ];
            });

        // Fusionner tous les événements
        $events = $tasks->concat($projects)->concat($invoices);

        return view('calendar.index', compact('events'));
    }

    /**
     * Récupérer les événements pour un mois donné (AJAX)
     */
    public function getEvents(Request $request)
    {
        $companyId = Auth::user()->company_id;
        $start = $request->get('start');
        $end = $request->get('end');

        // Récupérer les tâches
        $tasks = Task::where('company_id', $companyId)
            ->whereBetween('due_date', [$start, $end])
            ->get()
            ->map(function($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'start' => $task->due_date->format('Y-m-d'),
                    'end' => $task->due_date->format('Y-m-d'),
                    'type' => 'task',
                    'color' => $this->getTaskColor($task->status, $task->priority),
                    'url' => route('tasks.show', $task->id)
                ];
            });

        // Récupérer les projets
        $projects = Project::where('company_id', $companyId)
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('due_date', [$start, $end]);
            })
            ->get()
            ->map(function($project) {
                $events = [];
                if ($project->start_date) {
                    $events[] = [
                        'id' => $project->id . '_start',
                        'title' => 'Début: ' . $project->name,
                        'start' => $project->start_date->format('Y-m-d'),
                        'color' => '#10B981',
                        'url' => route('projects.show', $project->id)
                    ];
                }
                if ($project->due_date) {
                    $events[] = [
                        'id' => $project->id . '_end',
                        'title' => 'Fin: ' . $project->name,
                        'start' => $project->due_date->format('Y-m-d'),
                        'color' => '#EF4444',
                        'url' => route('projects.show', $project->id)
                    ];
                }
                return $events;
            })->flatten(1);

        return response()->json($tasks->concat($projects));
    }

    /**
     * Déterminer la couleur d'une tâche selon son statut et priorité
     */
    private function getTaskColor($status, $priority)
    {
        if ($status === 'completed') {
            return '#10B981'; // vert
        }

        if ($priority === 'high') {
            return '#EF4444'; // rouge
        }

        if ($priority === 'medium') {
            return '#F59E0B'; // orange
        }

        if ($status === 'in_progress') {
            return '#3B82F6'; // bleu
        }

        return '#6B7280'; // gris
    }
}
