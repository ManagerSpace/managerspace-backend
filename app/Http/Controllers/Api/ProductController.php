<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            Log::info('ProductController index method called', ['request' => $request->all()]);

            $user = Auth::user();
            if (!$user) {
                Log::warning('Unauthenticated user tried to access products');
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            $perPage = (int)$request->input('per_page', 15);
            $page = max(1, (int)$request->input('page', 1)); // Ensure page is at least 1

            $query = Product::whereHas('invoice.company', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with(['invoice', 'tax', 'category']);

            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->input('name') . '%');
            }

            if ($request->filled('category')) {
                $query->where('category_id', $request->input('category'));
            }

            if ($request->filled('price_min')) {
                $query->where('price', '>=', (float)$request->input('price_min'));
            }

            if ($request->filled('price_max')) {
                $query->where('price', '<=', (float)$request->input('price_max'));
            }

            if ($request->filled('tax_id')) {
                $query->where('tax_id', (int)$request->input('tax_id'));
            }

            Log::info('Query built', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

            $products = $query->paginate($perPage, ['*'], 'page', $page);

            Log::info('Products retrieved', ['count' => $products->count()]);

            return ProductResource::collection($products);
        } catch (\Exception $e) {
            Log::error('Error in ProductController index method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function store(StoreProductRequest $request)
    {
        $validatedData = $request->validated();
        $product = Product::create($validatedData);
        return new ProductResource($product);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validatedData = $request->validated();
        $product->update($validatedData);
        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
