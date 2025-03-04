<?php
namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Company;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['category', 'company'])->paginate(10);
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $categories = ExpenseCategory::all();
        $companies = Company::all();
        return view('expenses.create', compact('categories', 'companies'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'id_company' => 'required|exists:companies,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurrence_frequency' => 'nullable|string|in:weekly,monthly,yearly',
            'tax_deductible' => 'boolean',
        ]);

        $expense = Expense::create($validatedData);

        return redirect()->route('expenses.show', $expense)
            ->with('success', 'Gasto creado exitosamente.');
    }

    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::all();
        $companies = Company::all();
        return view('expenses.edit', compact('expense', 'categories', 'companies'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'id_company' => 'required|exists:companies,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurrence_frequency' => 'nullable|string|in:weekly,monthly,yearly',
            'tax_deductible' => 'boolean',
        ]);

        $expense->update($validatedData);

        return redirect()->route('expenses.show', $expense)
            ->with('success', 'Gasto actualizado exitosamente.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Gasto eliminado exitosamente.');
    }
}
