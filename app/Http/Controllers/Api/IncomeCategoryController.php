<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IncomeCategory;
use Illuminate\Http\Request;
use App\Http\Resources\IncomeCategoryResource;
use Illuminate\Support\Facades\Auth;

class IncomeCategoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $categories = IncomeCategory::where('user_id', $user->id)->latest()->get();
        return IncomeCategoryResource::collection($categories);
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

        $category = IncomeCategory::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return new IncomeCategoryResource($category);
    }

    public function show(IncomeCategory $incomeCategory)
    {
        if (Auth::id() !== $incomeCategory->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return new IncomeCategoryResource($incomeCategory);
    }

    public function update(Request $request, IncomeCategory $incomeCategory)
    {
        if (Auth::id() !== $incomeCategory->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $incomeCategory->update($validated);

        return new IncomeCategoryResource($incomeCategory);
    }

    public function destroy(IncomeCategory $incomeCategory)
    {
        if (Auth::id() !== $incomeCategory->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($incomeCategory->incomes()->count() > 0) {
            return response()->json(['error' => 'Cannot delete category with associated incomes'], 422);
        }

        $incomeCategory->delete();

        return response()->json(null, 204);
    }
}
