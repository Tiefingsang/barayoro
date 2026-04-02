<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Project;
use App\Models\Task;
use App\Models\Product;
use App\Models\Client;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $companyId = $this->getCompanyId();

        // Récupérer le nombre total d'utilisateurs
        $totalUsers = User::where('company_id', $companyId)->count();

        // Récupérer les ventes du mois en cours
        $currentMonthSales = Invoice::where('company_id', $companyId)
            ->where('status', 'paid')
            ->whereMonth('paid_date', now()->month)
            ->whereYear('paid_date', now()->year)
            ->sum('total');

        // Récupérer les ventes du mois dernier pour comparaison
        $lastMonthSales = Invoice::where('company_id', $companyId)
            ->where('status', 'paid')
            ->whereMonth('paid_date', now()->subMonth()->month)
            ->whereYear('paid_date', now()->subMonth()->year)
            ->sum('total');

        // Calculer le pourcentage d'évolution
        $salesGrowth = $lastMonthSales > 0
            ? (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100
            : ($currentMonthSales > 0 ? 100 : 0);

        // Récupérer les factures en attente
        $pendingInvoices = Invoice::where('company_id', $companyId)
            ->where('status', 'pending')
            ->where('due_date', '>=', now())
            ->sum('balance');

        // Récupérer les factures en retard
        $overdueInvoices = Invoice::where('company_id', $companyId)
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->sum('balance');

        // Récupérer les dépenses du mois
        $monthlyExpenses = Expense::where('company_id', $companyId)
            ->where('status', 'paid')
            ->whereMonth('paid_date', now()->month)
            ->whereYear('paid_date', now()->year)
            ->sum('total_amount');

        // Statistiques pour le graphique
        $monthlySalesData = [];
        $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];

        for ($i = 1; $i <= 12; $i++) {
            $monthlySalesData[] = Invoice::where('company_id', $companyId)
                ->where('status', 'paid')
                ->whereMonth('paid_date', $i)
                ->whereYear('paid_date', now()->year)
                ->sum('total');
        }

        // Activités récentes (combinaison de plusieurs tables)
        $recentActivities = collect();

        // Dernières factures
        $recentInvoices = Invoice::where('company_id', $companyId)
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($invoice) {
                return (object)[
                    'type' => 'invoice',
                    'title' => 'Facture créée',
                    'description' => "Facture {$invoice->invoice_number} pour {$invoice->client->name}",
                    'amount' => $invoice->total,
                    'created_at' => $invoice->created_at,
                    'icon' => 'las la-file-invoice',
                    'icon_color' => 'text-primary-300',
                    'bg_color' => 'bg-primary-50'
                ];
            });

        // Derniers paiements
        $recentPayments = Payment::where('company_id', $companyId)
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($payment) {
                return (object)[
                    'type' => 'payment',
                    'title' => 'Paiement reçu',
                    'description' => "Paiement de {$payment->client->name}",
                    'amount' => $payment->amount,
                    'created_at' => $payment->created_at,
                    'icon' => 'las la-credit-card',
                    'icon_color' => 'text-success-300',
                    'bg_color' => 'bg-success-50'
                ];
            });

        // Derniers clients
        $recentClients = Client::where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get()
            ->map(function($client) {
                return (object)[
                    'type' => 'client',
                    'title' => 'Nouveau client',
                    'description' => "{$client->name} a rejoint la plateforme",
                    'amount' => null,
                    'created_at' => $client->created_at,
                    'icon' => 'las la-user-plus',
                    'icon_color' => 'text-warning-300',
                    'bg_color' => 'bg-warning-50'
                ];
            });

        $recentActivities = $recentInvoices->concat($recentPayments)->concat($recentClients)
            ->sortByDesc('created_at')
            ->take(5);

        // Dernières tâches
        $recentTasks = Task::where('company_id', $companyId)
            ->with(['assignee', 'project'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Projets en cours
        $activeProjects = Project::where('company_id', $companyId)
            ->whereIn('status', ['planned', 'in_progress'])
            ->with(['client', 'manager'])
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // Factures en attente
        $pendingInvoicesList = Invoice::where('company_id', $companyId)
            ->where('status', 'pending')
            ->with('client')
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // Produits en stock faible
        $lowStockProducts = Product::where('company_id', $companyId)
            ->whereColumn('stock_quantity', '<=', 'min_stock_quantity')
            ->where('stock_quantity', '>', 0)
            ->limit(5)
            ->get();

        // Statistiques pour les cartes
        $stats = [
            'total_users' => $totalUsers,
            'total_sales' => $currentMonthSales,
            'sales_growth' => round($salesGrowth, 1),
            'pending_invoices' => $pendingInvoices,
            'overdue_invoices' => $overdueInvoices,
            'monthly_expenses' => $monthlyExpenses,
            'total_clients' => Client::where('company_id', $companyId)->count(),
            'low_stock_count' => Product::where('company_id', $companyId)
                ->whereColumn('stock_quantity', '<=', 'min_stock_quantity')
                ->count(),
        ];

        return view('dashboard.index', compact(
            'stats',
            'monthlySalesData',
            'months',
            'recentActivities',
            'recentTasks',
            'activeProjects',
            'pendingInvoicesList',
            'lowStockProducts'
        ));
    }


     /**
     * Get the company ID for the current authenticated user
     */
    protected function getCompanyId()
    {
        $user = Auth::user();

        // If user has a company relationship directly
        if ($user && $user->company_id) {
            return $user->company_id;
        }

        // Alternative: if user belongs to a company through a relationship
        if ($user && $user->company) {
            return $user->company->id;
        }

        // Return default or throw exception
        return null;
    }
}
