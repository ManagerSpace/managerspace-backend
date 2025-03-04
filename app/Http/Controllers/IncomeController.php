<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\IncomeCategory;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {
        $incomes = Income::with('category')->latest()->paginate(10);
        return view('incomes.index', compact('incomes'));
    }

    public function create()
    {
        $categories = IncomeCategory::all();
        return view('incomes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:income_categories,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'is_recurring' => 'boolean',
            'recurrence_frequency' => 'required_if:is_recurring,1|in:weekly,monthly,yearly',
        ]);

        $income = Income::create($validatedData);

        return redirect()->route('incomes.show', $income)
            ->with('success', 'Ingreso creado exitosamente.');
    }

    public function show(Income $income)
    {
        return view('incomes.show', compact('income'));
    }

    public function edit(Income $income)
    {
        $categories = IncomeCategory::all();
        return view('incomes.edit', compact('income', 'categories'));
    }

    public function update(Request $request, Income $income)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:income_categories,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'is_recurring' => 'boolean',
            'recurrence_frequency' => 'required_if:is_recurring,1|in:weekly,monthly,yearly',
        ]);

        $income->update($validatedData);

        return redirect()->route('incomes.show', $income)
            ->with('success', 'Ingreso actualizado exitosamente.');
    }

    public function destroy(Income $income)
    {
        $income->delete();

        return redirect()->route('incomes.index')
            ->with('success', 'Ingreso eliminado exitosamente.');
    }
}
