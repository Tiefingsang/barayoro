<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Activity;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ReportController extends Controller
{
    /**
     * Page principale des rapports
     */
    public function index()
    {
        $reports = Report::where('company_id', Auth::user()->company_id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('reports.index', compact('reports'));
    }

    /**
     * Formulaire de création de rapport
     */
    public function create()
    {
        $users = User::where('company_id', Auth::user()->company_id)
            ->where('is_active', true)
            ->get();

        $projects = Project::where('company_id', Auth::user()->company_id)
            ->get();

        $clients = Client::where('company_id', Auth::user()->company_id)
            ->where('status', 'active')
            ->get();

        return view('reports.create', compact('users', 'projects', 'clients'));
    }

    /**
     * Générer un rapport journalier
     */
    public function daily(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $startDate = Carbon::parse($date)->startOfDay();
        $endDate = Carbon::parse($date)->endOfDay();

        $data = $this->generateReportData($startDate, $endDate, $request);
        $format = $request->get('format', 'html');

        if ($format === 'pdf') {
            return $this->exportPdf($data, 'daily', $date);
        } elseif ($format === 'word') {
            return $this->exportWord($data, 'daily', $date);
        }

        return view('reports.daily', compact('data', 'date'));
    }

    /**
     * Générer un rapport hebdomadaire
     */
    public function weekly(Request $request)
    {
        $weekStart = $request->get('week_start', now()->startOfWeek()->format('Y-m-d'));
        $startDate = Carbon::parse($weekStart)->startOfDay();
        $endDate = Carbon::parse($weekStart)->endOfWeek()->endOfDay();

        $data = $this->generateReportData($startDate, $endDate, $request);
        $format = $request->get('format', 'html');

        if ($format === 'pdf') {
            return $this->exportPdf($data, 'weekly', $weekStart);
        } elseif ($format === 'word') {
            return $this->exportWord($data, 'weekly', $weekStart);
        }

        return view('reports.weekly', compact('data', 'weekStart', 'startDate', 'endDate'));
    }

    /**
     * Générer un rapport mensuel
     */
    public function monthly(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $startDate = Carbon::parse($month)->startOfMonth()->startOfDay();
        $endDate = Carbon::parse($month)->endOfMonth()->endOfDay();

        $data = $this->generateReportData($startDate, $endDate, $request);
        $format = $request->get('format', 'html');

        if ($format === 'pdf') {
            return $this->exportPdf($data, 'monthly', $month);
        } elseif ($format === 'word') {
            return $this->exportWord($data, 'monthly', $month);
        }

        return view('reports.monthly', compact('data', 'month', 'startDate', 'endDate'));
    }

    /**
     * Générer un rapport trimestriel
     */
    public function quarterly(Request $request)
    {
        $quarter = $request->get('quarter', now()->quarter);
        $year = $request->get('year', now()->year);

        $startDate = Carbon::create($year, ($quarter - 1) * 3 + 1, 1)->startOfDay();
        $endDate = Carbon::create($year, ($quarter - 1) * 3 + 3, 1)->endOfMonth()->endOfDay();

        $data = $this->generateReportData($startDate, $endDate, $request);
        $format = $request->get('format', 'html');

        if ($format === 'pdf') {
            return $this->exportPdf($data, 'quarterly', "Q{$quarter}-{$year}");
        } elseif ($format === 'word') {
            return $this->exportWord($data, 'quarterly', "Q{$quarter}-{$year}");
        }

        return view('reports.quarterly', compact('data', 'quarter', 'year', 'startDate', 'endDate'));
    }

    /**
     * Générer un rapport annuel
     */
    public function annual(Request $request)
    {
        $year = $request->get('year', now()->year);
        $startDate = Carbon::create($year, 1, 1)->startOfDay();
        $endDate = Carbon::create($year, 12, 31)->endOfDay();

        $data = $this->generateReportData($startDate, $endDate, $request);
        $format = $request->get('format', 'html');

        if ($format === 'pdf') {
            return $this->exportPdf($data, 'annual', $year);
        } elseif ($format === 'word') {
            return $this->exportWord($data, 'annual', $year);
        }

        return view('reports.annual', compact('data', 'year', 'startDate', 'endDate'));
    }

    /**
     * Générer un rapport personnalisé
     */
    public function custom(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $data = $this->generateReportData($startDate, $endDate, $request);
        $format = $request->get('format', 'html');

        if ($format === 'pdf') {
            return $this->exportPdf($data, 'custom', "{$startDate->format('Y-m-d')}_to_{$endDate->format('Y-m-d')}");
        } elseif ($format === 'word') {
            return $this->exportWord($data, 'custom', "{$startDate->format('Y-m-d')}_to_{$endDate->format('Y-m-d')}");
        }

        return view('reports.custom', compact('data', 'startDate', 'endDate'));
    }

    /**
     * Générer les données du rapport
     */
    private function generateReportData($startDate, $endDate, $request)
    {
        $companyId = Auth::user()->company_id;

        // Filtrer par utilisateur
        $userIds = $request->get('user_ids', []);
        // Filtrer par projet
        $projectIds = $request->get('project_ids', []);
        // Filtrer par client
        $clientIds = $request->get('client_ids', []);

        // Requête de base pour les tâches
        $taskQuery = Task::where('company_id', $companyId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if (!empty($userIds)) {
            $taskQuery->whereIn('assigned_to', $userIds);
        }
        if (!empty($projectIds)) {
            $taskQuery->whereIn('project_id', $projectIds);
        }

        $tasks = $taskQuery->get();

        // Requête pour les projets
        $projectQuery = Project::where('company_id', $companyId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if (!empty($clientIds)) {
            $projectQuery->whereIn('client_id', $clientIds);
        }

        $projects = $projectQuery->get();

        // Requête pour les factures
        $invoiceQuery = Invoice::where('company_id', $companyId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        $invoices = $invoiceQuery->get();

        // Statistiques par utilisateur
// Statistiques par utilisateur
$userStats = collect(); // Collection
$users = User::where('company_id', $companyId)
    ->when(!empty($userIds), function($q) use ($userIds) {
        return $q->whereIn('id', $userIds);
    })
    ->get();

foreach ($users as $user) {
    $userTasks = $tasks->where('assigned_to', $user->id);
    $userStats->push([
        'user' => $user,
        'tasks_completed' => $userTasks->where('status', 'completed')->count(),
        'tasks_in_progress' => $userTasks->where('status', 'in_progress')->count(),
        'tasks_pending' => $userTasks->where('status', 'pending')->count(),
        'total_tasks' => $userTasks->count(),
        'completion_rate' => $userTasks->count() > 0 ? round(($userTasks->where('status', 'completed')->count() / $userTasks->count()) * 100, 2) : 0,
    ]);
}


        // Statistiques par projet
        $projectStats = [];
        foreach ($projects as $project) {
            $projectTasks = $tasks->where('project_id', $project->id);
            $projectStats[$project->id] = [
                'project' => $project,
                'tasks_completed' => $projectTasks->where('status', 'completed')->count(),
                'tasks_in_progress' => $projectTasks->where('status', 'in_progress')->count(),
                'total_tasks' => $projectTasks->count(),
                'progress' => $project->progress,
                'budget' => $project->budget,
                'actual_cost' => $project->actual_cost,
            ];
        }

        // Activités récentes
        $activities = Activity::where('company_id', $companyId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        // Résumé global
        $summary = [
            'period' => [
                'start' => $startDate->format('d/m/Y H:i'),
                'end' => $endDate->format('d/m/Y H:i'),
                'days' => $startDate->diffInDays($endDate) + 1,
            ],
            'tasks' => [
                'total' => $tasks->count(),
                'completed' => $tasks->where('status', 'completed')->count(),
                'in_progress' => $tasks->where('status', 'in_progress')->count(),
                'pending' => $tasks->where('status', 'pending')->count(),
                'completion_rate' => $tasks->count() > 0 ? round(($tasks->where('status', 'completed')->count() / $tasks->count()) * 100, 2) : 0,
            ],
            'projects' => [
                'total' => $projects->count(),
                'active' => $projects->whereIn('status', ['planned', 'in_progress'])->count(),
                'completed' => $projects->where('status', 'completed')->count(),
            ],
            'invoices' => [
                'total' => $invoices->count(),
                'total_amount' => $invoices->sum('total'),
                'paid_amount' => $invoices->where('status', 'paid')->sum('total'),
                'pending_amount' => $invoices->where('status', 'pending')->sum('total'),
            ],
            // ... plus tard dans le résumé
'users' => [
    'active' => $users->where('is_active', true)->count(),
    'with_tasks' => $userStats->filter(fn($s) => $s['total_tasks'] > 0)->count(),
],

        ];

        // Données pour graphiques
        $chartData = $this->generateChartData($startDate, $endDate, $companyId, $userIds, $projectIds);

        return [
            'summary' => $summary,
            'user_stats' => $userStats,
            'project_stats' => $projectStats,
            'activities' => $activities,
            'tasks' => $tasks,
            'projects' => $projects,
            'invoices' => $invoices,
            'chart_data' => $chartData,
            'generated_at' => now(),
            'generated_by' => Auth::user(),
        ];
    }

    /**
     * Générer les données pour les graphiques
     */
  /**
 * Générer les données pour les graphiques
 */
private function generateChartData($startDate, $endDate, $companyId, $userIds, $projectIds)
{
    $period = CarbonPeriod::create($startDate, $endDate);
    $dailyTasks = [];
    $dailyCompleted = [];
    $labels = [];

    foreach ($period as $date) {
        $labels[] = $date->format('d/m');

        $dayTasks = Task::where('company_id', $companyId)
            ->whereDate('created_at', $date)
            ->when(!empty($userIds), function($q) use ($userIds) {
                return $q->whereIn('assigned_to', $userIds);
            })
            ->when(!empty($projectIds), function($q) use ($projectIds) {
                return $q->whereIn('project_id', $projectIds);
            })
            ->count();

        $dayCompleted = Task::where('company_id', $companyId)
            ->where('status', 'completed')
            ->whereDate('completed_at', $date)
            ->when(!empty($userIds), function($q) use ($userIds) {
                return $q->whereIn('assigned_to', $userIds);
            })
            ->when(!empty($projectIds), function($q) use ($projectIds) {
                return $q->whereIn('project_id', $projectIds);
            })
            ->count();

        $dailyTasks[] = $dayTasks;
        $dailyCompleted[] = $dayCompleted;
    }

    return [
        'labels' => $labels,
        'tasks' => $dailyTasks,
        'completed' => $dailyCompleted,
    ];
}

    /**
     * Exporter en PDF
     */
    private function exportPdf($data, $type, $period)
    {
        $pdf = Pdf::loadView("reports.exports.{$type}", compact('data', 'period'));
        $pdf->setPaper('A4', 'portrait');

        $filename = "rapport_{$type}_{$period}_" . now()->format('Y-m-d_H-i') . ".pdf";

        // Sauvegarder le rapport
        $this->saveReport($data, $type, 'pdf', $filename);

        return $pdf->download($filename);
    }

    /**
     * Exporter en Word
     */
    private function exportWord($data, $type, $period)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Titre
        $section->addTitle("Rapport " . ucfirst($type), 1);
        $section->addText("Période: {$period}");
        $section->addText("Généré le: " . now()->format('d/m/Y H:i'));
        $section->addTextBreak(1);

        // Résumé
        $section->addTitle("Résumé", 2);
        $section->addText("Total des tâches: {$data['summary']['tasks']['total']}");
        $section->addText("Tâches terminées: {$data['summary']['tasks']['completed']}");
        $section->addText("Taux de complétion: {$data['summary']['tasks']['completion_rate']}%");
        $section->addTextBreak(1);

        // Tâches par utilisateur
        $section->addTitle("Performance par utilisateur", 2);
        $table = $section->addTable();
        $table->addRow();
        $table->addCell(3000)->addText("Utilisateur");
        $table->addCell(2000)->addText("Tâches totales");
        $table->addCell(2000)->addText("Terminées");
        $table->addCell(2000)->addText("Taux");

        foreach ($data['user_stats'] as $stat) {
            $table->addRow();
            $table->addCell(3000)->addText($stat['user']->name);
            $table->addCell(2000)->addText($stat['total_tasks']);
            $table->addCell(2000)->addText($stat['tasks_completed']);
            $table->addCell(2000)->addText($stat['completion_rate'] . '%');
        }

        // Sauvegarder le fichier Word
        $filename = "rapport_{$type}_{$period}_" . now()->format('Y-m-d_H-i') . ".docx";
        $tempFile = storage_path("app/temp/{$filename}");

        if (!is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0777, true);
        }

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        // Sauvegarder le rapport
        $this->saveReport($data, $type, 'word', $filename);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Sauvegarder le rapport
     */
    private function saveReport($data, $type, $format, $filename)
    {
        Report::create([
            'company_id' => Auth::user()->company_id,
            'user_id' => Auth::id(),
            'name' => "Rapport {$type} - " . now()->format('d/m/Y H:i'),
            'type' => $type,
            'format' => $format,
            'filters' => ['period' => $data['summary']['period']],
            'data' => $data,
            'generated_at' => now(),
        ]);
    }

    /**
     * Télécharger un rapport sauvegardé
     */
    public function download(Report $report)
    {
        $this->checkAccess($report);

        $report->increment('download_count');

        // Générer à nouveau le fichier si nécessaire
        if (!$report->file_path || !Storage::exists($report->file_path)) {
            if ($report->format === 'pdf') {
                return $this->exportPdf($report->data, $report->type, $report->generated_at->format('Y-m-d'));
            } else {
                return $this->exportWord($report->data, $report->type, $report->generated_at->format('Y-m-d'));
            }
        }

        return Storage::download($report->file_path);
    }

    /**
     * Supprimer un rapport
     */
    public function destroy(Report $report)
    {
        $this->checkAccess($report);

        if ($report->file_path && Storage::exists($report->file_path)) {
            Storage::delete($report->file_path);
        }

        $report->delete();

        return redirect()->route('reports.index')->with('success', 'Rapport supprimé avec succès.');
    }

    /**
     * Vérifier l'accès
     */
    private function checkAccess($report)
    {
        if ($report->company_id !== Auth::user()->company_id) {
            abort(403, 'Accès non autorisé.');
        }
    }
}
