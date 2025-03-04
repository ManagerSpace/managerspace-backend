<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use App\Http\Resources\ExpenseCategoryResource;
use Illuminate\Support\Facades\Auth;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $categories = ExpenseCategory::where('user_id', $user->id)->latest()->get();
        return ExpenseCategoryResource::collection($categories);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = ExpenseCategory::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return new ExpenseCategoryResource($category);
    }

    public function show(ExpenseCategory $expenseCategory)
    {
        if (Auth::id() !== $expenseCategory->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return new ExpenseCategoryResource($expenseCategory);
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        if (Auth::id() !== $expenseCategory->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $expenseCategory->update($validated);

        return new ExpenseCategoryResource($expenseCategory);
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        if (Auth::id() !== $expenseCategory->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($expenseCategory->expenses()->count() > 0) {
            return response()->json(['error' => 'Cannot delete category with associated expenses'], 422);
        }

        $expenseCategory->delete();

        return response()->json(null, 204);
    }
}
