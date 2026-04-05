<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;

        // Statistiques
        $stats = [
            'total_tasks' => Task::where('company_id', $companyId)->count(),
            'completed_tasks' => Task::where('company_id', $companyId)->where('status', 'completed')->count(),
            'in_progress_tasks' => Task::where('company_id', $companyId)->where('status', 'in_progress')->count(),
            'total_projects' => Project::where('company_id', $companyId)->count(),
            'completed_projects' => Project::where('company_id', $companyId)->where('status', 'completed')->count(),
            'total_clients' => Client::where('company_id', $companyId)->count(),
            'total_invoices' => Invoice::where('company_id', $companyId)->count(),
            'total_revenue' => Invoice::where('company_id', $companyId)->where('status', 'paid')->sum('total'),
        ];

        return view('analytics.index', compact('stats'));
    }
}
