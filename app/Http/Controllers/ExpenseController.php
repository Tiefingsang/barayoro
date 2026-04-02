<?php
// app/Http/Controllers/ExpenseController.php
namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Project;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->checkPermission('view_expenses');

        $query = Expense::where('company_id', $this->getCompanyId())
            ->with(['category', 'project', 'vendor', 'creator', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('expense_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(15);

        $categories = ExpenseCategory::where('company_id', $this->getCompanyId())
            ->where('is_active', true)
            ->get();

        $projects = Project::where('company_id', $this->getCompanyId())->get();

        $statusCounts = [
            'pending' => Expense::where('company_id', $this->getCompanyId())->where('status', 'pending')->count(),
            'approved' => Expense::where('company_id', $this->getCompanyId())->where('status', 'approved')->count(),
            'paid' => Expense::where('company_id', $this->getCompanyId())->where('status', 'paid')->count(),
            'rejected' => Expense::where('company_id', $this->getCompanyId())->where('status', 'rejected')->count(),
        ];

        $totalAmount = Expense::where('company_id', $this->getCompanyId())
            ->whereIn('status', ['approved', 'paid'])
            ->sum('amount');

        return view('expenses.index', compact('expenses', 'categories', 'projects', 'statusCounts', 'totalAmount'));
    }

    public function create()
    {
        $this->checkPermission('create_expenses');

        $categories = ExpenseCategory::where('company_id', $this->getCompanyId())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $projects = Project::where('company_id', $this->getCompanyId())
            ->whereIn('status', ['planned', 'in_progress'])
            ->orderBy('name')
            ->get();

        $vendors = Client::where('company_id', $this->getCompanyId())
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $expenseNumber = $this->generateExpenseNumber();

        return view('expenses.create', compact('categories', 'projects', 'vendors', 'expenseNumber'));
    }

    public function store(Request $request)
    {
        $this->checkPermission('create_expenses');

        $validated = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'project_id' => 'nullable|exists:projects,id',
            'vendor_id' => 'nullable|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'expense_date' => 'required|date',
            'due_date' => 'nullable|date',
            'payment_method' => 'nullable|in:cash,bank_transfer,check,credit_card,mobile_money,other',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $validated['company_id'] = $this->getCompanyId();
        $validated['created_by'] = auth()->id();
        $validated['expense_number'] = $this->generateExpenseNumber();
        $validated['total_amount'] = $validated['amount'] + ($validated['tax_amount'] ?? 0);
        $validated['status'] = 'pending';

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('expenses', 'public');
            $validated['receipt_path'] = $path;
        }

        DB::beginTransaction();

        try {
            $expense = Expense::create($validated);

            // Mettre à jour le coût réel du projet
            if ($expense->project_id && in_array($expense->status, ['approved', 'paid'])) {
                $this->updateProjectCost($expense->project);
            }

            DB::commit();

            return redirect()->route('expenses.show', $expense)
                ->with('success', 'Dépense créée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la création de la dépense: ' . $e->getMessage());
        }
    }

    public function show(Expense $expense)
    {
        $this->checkPermission('view_expenses');
        $this->checkCompanyAccess($expense);

        $expense->load(['category', 'project', 'vendor', 'creator', 'approver', 'paidBy']);

        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $this->checkPermission('edit_expenses');
        $this->checkCompanyAccess($expense);

        if ($expense->status !== 'pending') {
            return redirect()->route('expenses.show', $expense)
                ->with('error', 'Seules les dépenses en attente peuvent être modifiées.');
        }

        $categories = ExpenseCategory::where('company_id', $this->getCompanyId())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $projects = Project::where('company_id', $this->getCompanyId())
            ->whereIn('status', ['planned', 'in_progress'])
            ->orderBy('name')
            ->get();

        $vendors = Client::where('company_id', $this->getCompanyId())
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('expenses.edit', compact('expense', 'categories', 'projects', 'vendors'));
    }

    public function update(Request $request, Expense $expense)
    {
        $this->checkPermission('edit_expenses');
        $this->checkCompanyAccess($expense);

        if ($expense->status !== 'pending') {
            return redirect()->route('expenses.show', $expense)
                ->with('error', 'Seules les dépenses en attente peuvent être modifiées.');
        }

        $validated = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'project_id' => 'nullable|exists:projects,id',
            'vendor_id' => 'nullable|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'expense_date' => 'required|date',
            'due_date' => 'nullable|date',
            'payment_method' => 'nullable|in:cash,bank_transfer,check,credit_card,mobile_money,other',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $validated['total_amount'] = $validated['amount'] + ($validated['tax_amount'] ?? 0);

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('expenses', 'public');
            $validated['receipt_path'] = $path;
        }

        DB::beginTransaction();

        try {
            $oldProjectId = $expense->project_id;
            $expense->update($validated);

            // Mettre à jour les coûts des projets
            if ($oldProjectId) {
                $this->updateProjectCost(Project::find($oldProjectId));
            }
            if ($expense->project_id && $expense->project_id != $oldProjectId) {
                $this->updateProjectCost($expense->project);
            }

            DB::commit();

            return redirect()->route('expenses.show', $expense)
                ->with('success', 'Dépense mise à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la mise à jour de la dépense: ' . $e->getMessage());
        }
    }

    public function destroy(Expense $expense)
    {
        $this->checkPermission('delete_expenses');
        $this->checkCompanyAccess($expense);

        if ($expense->status !== 'pending') {
            return back()->with('error', 'Seules les dépenses en attente peuvent être supprimées.');
        }

        DB::beginTransaction();

        try {
            $projectId = $expense->project_id;
            $expense->delete();

            if ($projectId) {
                $this->updateProjectCost(Project::find($projectId));
            }

            DB::commit();

            return redirect()->route('expenses.index')
                ->with('success', 'Dépense supprimée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la suppression de la dépense: ' . $e->getMessage());
        }
    }

    public function approve(Expense $expense)
    {
        $this->checkPermission('approve_expenses');
        $this->checkCompanyAccess($expense);

        if ($expense->status !== 'pending') {
            return response()->json(['error' => 'Cette dépense ne peut pas être approuvée.'], 422);
        }

        DB::beginTransaction();

        try {
            $expense->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Mettre à jour le coût du projet
            if ($expense->project_id) {
                $this->updateProjectCost($expense->project);
            }

            // Notification au créateur
            \App\Models\Notification::send(
                $expense->creator,
                'Dépense approuvée',
                "Votre dépense '{$expense->title}' a été approuvée.",
                'success',
                route('expenses.show', $expense)
            );

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Dépense approuvée avec succès.']);
            }

            return back()->with('success', 'Dépense approuvée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de l\'approbation: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Expense $expense)
    {
        $this->checkPermission('approve_expenses');
        $this->checkCompanyAccess($expense);

        if ($expense->status !== 'pending') {
            return response()->json(['error' => 'Cette dépense ne peut pas être rejetée.'], 422);
        }

        $request->validate([
            'reason' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $expense->update([
                'status' => 'rejected',
                'rejection_reason' => $request->reason,
                'rejected_at' => now(),
            ]);

            // Notification au créateur
            \App\Models\Notification::send(
                $expense->creator,
                'Dépense rejetée',
                "Votre dépense '{$expense->title}' a été rejetée. Raison: {$request->reason}",
                'error',
                route('expenses.edit', $expense)
            );

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Dépense rejetée.']);
            }

            return back()->with('success', 'Dépense rejetée.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors du rejet: ' . $e->getMessage());
        }
    }

    public function markAsPaid(Request $request, Expense $expense)
    {
        $this->checkPermission('pay_expenses');
        $this->checkCompanyAccess($expense);

        if (!in_array($expense->status, ['approved', 'pending'])) {
            return response()->json(['error' => 'Cette dépense ne peut pas être marquée comme payée.'], 422);
        }

        $request->validate([
            'payment_reference' => 'nullable|string|max:100',
            'paid_date' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            $expense->update([
                'status' => 'paid',
                'paid_by' => auth()->id(),
                'paid_date' => $request->paid_date,
                'payment_reference' => $request->payment_reference,
                'paid_at' => now(),
            ]);

            // Mettre à jour le coût du projet
            if ($expense->project_id) {
                $this->updateProjectCost($expense->project);
            }

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Dépense marquée comme payée.']);
            }

            return back()->with('success', 'Dépense marquée comme payée.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $this->checkPermission('export_reports');

        $query = Expense::where('company_id', $this->getCompanyId())
            ->with(['category', 'project', 'vendor']);

        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->get();

        $filename = 'depenses_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://temp', 'w+');

        fputcsv($handle, ['N°', 'Titre', 'Catégorie', 'Projet', 'Montant', 'Taxe', 'Total', 'Date', 'Statut']);

        foreach ($expenses as $expense) {
            fputcsv($handle, [
                $expense->expense_number,
                $expense->title,
                $expense->category->name ?? 'N/A',
                $expense->project->name ?? 'N/A',
                number_format($expense->amount, 0, ',', ' ') . ' FCFA',
                number_format($expense->tax_amount ?? 0, 0, ',', ' ') . ' FCFA',
                number_format($expense->total_amount, 0, ',', ' ') . ' FCFA',
                $expense->expense_date,
                $expense->status,
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function generateExpenseNumber()
    {
        $lastExpense = Expense::where('company_id', $this->getCompanyId())
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastExpense) {
            return 'EXP-001';
        }

        $lastNumber = intval(substr($lastExpense->expense_number, 4));
        $newNumber = $lastNumber + 1;

        return 'EXP-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    private function updateProjectCost($project)
    {
        if (!$project) return;

        $totalExpenses = $project->expenses()
            ->whereIn('status', ['approved', 'paid'])
            ->sum('total_amount');

        $project->update(['actual_cost' => $totalExpenses]);
    }
}
