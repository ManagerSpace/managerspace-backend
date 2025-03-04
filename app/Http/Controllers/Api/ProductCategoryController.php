<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Resources\ProductCategoryResource;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $categories = ProductCategory::where('user_id', $user->id)->latest()->get();
        return ProductCategoryResource::collection($categories);
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

        $category = ProductCategory::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return new ProductCategoryResource($category);
    }

    public function show(ProductCategory $productCategory)
    {
        if (Auth::id() !== $productCategory->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return new ProductCategoryResource($productCategory);
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        if (Auth::id() !== $productCategory->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $productCategory->update($validated);

        return new ProductCategoryResource($productCategory);
    }

    public function destroy(ProductCategory $productCategory)
    {
        if (Auth::id() !== $productCategory->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($productCategory->products()->count() > 0) {
            return response()->json(['error' => 'Cannot delete category with associated products'], 422);
        }

        $productCategory->delete();

        return response()->json(null, 204);
    }
}
