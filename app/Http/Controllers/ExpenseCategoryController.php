<?php
// app/Http/Controllers/ExpenseCategoryController.php
namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExpenseCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage_settings')->except(['index']);
    }

    public function index(Request $request)
    {
        $categories = ExpenseCategory::where('company_id', $this->getCompanyId())
            ->with('parent')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        if ($request->wantsJson()) {
            return response()->json($categories);
        }

        return view('expense-categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = ExpenseCategory::where('company_id', $this->getCompanyId())
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('expense-categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:expense_categories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:expense_categories,code',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:20',
            'type' => 'required|in:operational,administrative,marketing,salary,tax,other',
            'is_taxable' => 'boolean',
            'is_billable' => 'boolean',
            'budget_limit' => 'nullable|numeric|min:0',
            'budget_period' => 'nullable|in:monthly,quarterly,yearly',
        ]);

        $validated['company_id'] = $this->getCompanyId();
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = true;
        $validated['is_taxable'] = $request->boolean('is_taxable', true);
        $validated['is_billable'] = $request->boolean('is_billable', true);

        $category = ExpenseCategory::create($validated);

        if ($request->wantsJson()) {
            return response()->json($category, 201);
        }

        return redirect()->route('expense-categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function show(ExpenseCategory $expenseCategory)
    {
        $this->checkCompanyAccess($expenseCategory);

        $expenseCategory->load(['parent', 'children', 'expenses']);

        $stats = [
            'total_expenses' => $expenseCategory->expenses()->sum('total_amount'),
            'expenses_count' => $expenseCategory->expenses()->count(),
            'budget_utilization' => $expenseCategory->budget_limit
                ? ($expenseCategory->total_expenses / $expenseCategory->budget_limit) * 100
                : null,
        ];

        return view('expense-categories.show', compact('expenseCategory', 'stats'));
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        $this->checkCompanyAccess($expenseCategory);

        $parents = ExpenseCategory::where('company_id', $this->getCompanyId())
            ->whereNull('parent_id')
            ->where('id', '!=', $expenseCategory->id)
            ->orderBy('name')
            ->get();

        return view('expense-categories.edit', compact('expenseCategory', 'parents'));
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $this->checkCompanyAccess($expenseCategory);

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:expense_categories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:expense_categories,code,' . $expenseCategory->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:20',
            'type' => 'required|in:operational,administrative,marketing,salary,tax,other',
            'is_taxable' => 'boolean',
            'is_billable' => 'boolean',
            'is_active' => 'boolean',
            'budget_limit' => 'nullable|numeric|min:0',
            'budget_period' => 'nullable|in:monthly,quarterly,yearly',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_taxable'] = $request->boolean('is_taxable', true);
        $validated['is_billable'] = $request->boolean('is_billable', true);
        $validated['is_active'] = $request->boolean('is_active', true);

        $expenseCategory->update($validated);

        if ($request->wantsJson()) {
            return response()->json($expenseCategory);
        }

        return redirect()->route('expense-categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        $this->checkCompanyAccess($expenseCategory);

        if ($expenseCategory->expenses()->exists()) {
            return back()->with('error', 'Cette catégorie contient des dépenses et ne peut pas être supprimée.');
        }

        if ($expenseCategory->children()->exists()) {
            return back()->with('error', 'Cette catégorie a des sous-catégories et ne peut pas être supprimée.');
        }

        $expenseCategory->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Catégorie supprimée avec succès.']);
        }

        return redirect()->route('expense-categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}
