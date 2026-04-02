<?php
// app/Http/Controllers/FinanceController.php
namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{

    /**
     * Check if the current user has a specific permission
     */
    protected function checkPermission($permission)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Check if user has permission (using Spatie's method)
        if (method_exists($user, 'hasPermissionTo') && !$user->hasPermissionTo($permission)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return true;
    }

    /**
     * Get the current user's company ID
     */
    protected function getCompanyId()
    {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        return $user->company_id ?? $user->company?->id;
    }

    public function index(Request $request)
    {
        $this->checkPermission('view_reports');

        $companyId = $this->getCompanyId();

        // Période par défaut: année en cours
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        // Chiffre d'affaires
        $revenue = Invoice::where('company_id', $companyId)
            ->where('status', 'paid')
            ->whereYear('paid_date', $year)
            ->sum('total');

        // Dépenses
        $expenses = Expense::where('company_id', $companyId)
            ->where('status', 'paid')
            ->whereYear('paid_date', $year)
            ->sum('total_amount');

        // Bénéfice
        $profit = $revenue - $expenses;

        // Revenus mensuels
        $monthlyRevenue = Invoice::where('company_id', $companyId)
            ->where('status', 'paid')
            ->whereYear('paid_date', $year)
            ->select(
                DB::raw('MONTH(paid_date) as month'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Dépenses mensuelles
        $monthlyExpenses = Expense::where('company_id', $companyId)
            ->where('status', 'paid')
            ->whereYear('paid_date', $year)
            ->select(
                DB::raw('MONTH(paid_date) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Paiements par méthode
        $paymentsByMethod = Payment::where('company_id', $companyId)
            ->where('status', 'completed')
            ->whereYear('payment_date', $year)
            ->select('method', DB::raw('SUM(amount) as total'))
            ->groupBy('method')
            ->get();

        // Dépenses par catégorie
        $expensesByCategory = Expense::where('company_id', $companyId)
            ->where('status', 'paid')
            ->whereYear('paid_date', $year)
            ->select('expense_category_id', DB::raw('SUM(total_amount) as total'))
            ->with('category')
            ->groupBy('expense_category_id')
            ->get();

        // Top clients
        $topClients = Invoice::where('company_id', $companyId)
            ->where('status', 'paid')
            ->whereYear('paid_date', $year)
            ->select('client_id', DB::raw('SUM(total) as total'))
            ->with('client')
            ->groupBy('client_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Factures impayées
        $pendingInvoices = Invoice::where('company_id', $companyId)
            ->where('status', 'pending')
            ->where('due_date', '>=', now())
            ->sum('balance');

        $overdueInvoices = Invoice::where('company_id', $companyId)
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->sum('balance');

        // Taux de recouvrement
        $totalInvoiced = Invoice::where('company_id', $companyId)
            ->whereYear('created_at', $year)
            ->sum('total');

        $collectionRate = $totalInvoiced > 0 ? ($revenue / $totalInvoiced) * 100 : 0;

        // Flux de trésorerie (simplifié)
        $cashFlow = [
            'revenue' => $revenue,
            'expenses' => $expenses,
            'net' => $profit,
        ];

        // Préparer les données pour les graphiques
        $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        $revenueData = [];
        $expenseData = [];

        for ($i = 1; $i <= 12; $i++) {
            $revenueData[] = $monthlyRevenue[$i] ?? 0;
            $expenseData[] = $monthlyExpenses[$i] ?? 0;
        }

        return view('finance.index', compact(
            'revenue',
            'expenses',
            'profit',
            'revenueData',
            'expenseData',
            'months',
            'paymentsByMethod',
            'expensesByCategory',
            'topClients',
            'pendingInvoices',
            'overdueInvoices',
            'collectionRate',
            'cashFlow',
            'year'
        ));
    }

    public function cashFlow(Request $request)
    {
        $this->checkPermission('view_reports');

        $companyId = $this->getCompanyId();
        $year = $request->get('year', date('Y'));

        $cashFlowData = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = "$year-$month-01";
            $endDate = date('Y-m-t', strtotime($startDate));

            $revenue = Invoice::where('company_id', $companyId)
                ->where('status', 'paid')
                ->whereBetween('paid_date', [$startDate, $endDate])
                ->sum('total');

            $expenses = Expense::where('company_id', $companyId)
                ->where('status', 'paid')
                ->whereBetween('paid_date', [$startDate, $endDate])
                ->sum('total_amount');

            $cashFlowData[] = [
                'month' => $month,
                'revenue' => $revenue,
                'expenses' => $expenses,
                'net' => $revenue - $expenses,
            ];
        }

        return response()->json($cashFlowData);
    }

    public function profitLoss(Request $request)
    {
        $this->checkPermission('view_reports');

        $companyId = $this->getCompanyId();
        $year = $request->get('year', date('Y'));

        // Revenus par type
        $revenueByType = [
            'invoices' => Invoice::where('company_id', $companyId)
                ->where('status', 'paid')
                ->whereYear('paid_date', $year)
                ->sum('total'),
        ];

        // Dépenses par type
        $expensesByType = [
            'operational' => Expense::where('company_id', $companyId)
                ->where('status', 'paid')
                ->whereYear('paid_date', $year)
                ->whereHas('category', function($q) {
                    $q->where('type', 'operational');
                })
                ->sum('total_amount'),

            'administrative' => Expense::where('company_id', $companyId)
                ->where('status', 'paid')
                ->whereYear('paid_date', $year)
                ->whereHas('category', function($q) {
                    $q->where('type', 'administrative');
                })
                ->sum('total_amount'),

            'marketing' => Expense::where('company_id', $companyId)
                ->where('status', 'paid')
                ->whereYear('paid_date', $year)
                ->whereHas('category', function($q) {
                    $q->where('type', 'marketing');
                })
                ->sum('total_amount'),

            'salary' => Expense::where('company_id', $companyId)
                ->where('status', 'paid')
                ->whereYear('paid_date', $year)
                ->whereHas('category', function($q) {
                    $q->where('type', 'salary');
                })
                ->sum('total_amount'),

            'tax' => Expense::where('company_id', $companyId)
                ->where('status', 'paid')
                ->whereYear('paid_date', $year)
                ->whereHas('category', function($q) {
                    $q->where('type', 'tax');
                })
                ->sum('total_amount'),

            'other' => Expense::where('company_id', $companyId)
                ->where('status', 'paid')
                ->whereYear('paid_date', $year)
                ->whereHas('category', function($q) {
                    $q->where('type', 'other');
                })
                ->sum('total_amount'),
        ];

        $totalRevenue = array_sum($revenueByType);
        $totalExpenses = array_sum($expensesByType);
        $netProfit = $totalRevenue - $totalExpenses;

        return view('finance.profit-loss', compact(
            'revenueByType',
            'expensesByType',
            'totalRevenue',
            'totalExpenses',
            'netProfit',
            'year'
        ));
    }

    public function agingReport(Request $request)
    {
        $this->checkPermission('view_reports');

        $companyId = $this->getCompanyId();

        $invoices = Invoice::where('company_id', $companyId)
            ->where('status', 'pending')
            ->where('balance', '>', 0)
            ->with('client')
            ->get();

        $aging = [
            '0-30' => 0,
            '31-60' => 0,
            '61-90' => 0,
            '90+' => 0,
        ];

        $agingData = [];

        foreach ($invoices as $invoice) {
            $daysOverdue = now()->diffInDays($invoice->due_date);

            if ($daysOverdue <= 30) {
                $aging['0-30'] += $invoice->balance;
                $agingData['0-30'][] = $invoice;
            } elseif ($daysOverdue <= 60) {
                $aging['31-60'] += $invoice->balance;
                $agingData['31-60'][] = $invoice;
            } elseif ($daysOverdue <= 90) {
                $aging['61-90'] += $invoice->balance;
                $agingData['61-90'][] = $invoice;
            } else {
                $aging['90+'] += $invoice->balance;
                $agingData['90+'][] = $invoice;
            }
        }

        $totalAging = array_sum($aging);

        return view('finance.aging', compact('aging', 'agingData', 'totalAging'));
    }

    public function export(Request $request)
    {
        $this->checkPermission('export_reports');

        $companyId = $this->getCompanyId();
        $year = $request->get('year', date('Y'));

        $revenue = Invoice::where('company_id', $companyId)
            ->where('status', 'paid')
            ->whereYear('paid_date', $year)
            ->sum('total');

        $expenses = Expense::where('company_id', $companyId)
            ->where('status', 'paid')
            ->whereYear('paid_date', $year)
            ->sum('total_amount');

        $filename = 'rapport_financier_' . $year . '.csv';
        $handle = fopen('php://temp', 'w+');

        fputcsv($handle, ['RAPPORT FINANCIER ' . $year]);
        fputcsv($handle, []);
        fputcsv($handle, ['Indicateur', 'Montant']);
        fputcsv($handle, ['Chiffre d\'affaires', number_format($revenue, 0, ',', ' ') . ' FCFA']);
        fputcsv($handle, ['Dépenses', number_format($expenses, 0, ',', ' ') . ' FCFA']);
        fputcsv($handle, ['Bénéfice', number_format($revenue - $expenses, 0, ',', ' ') . ' FCFA']);

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
