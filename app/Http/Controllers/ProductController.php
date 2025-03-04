<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Tax;
use App\Models\Invoice;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'tax', 'invoice'])->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        $taxes = Tax::all();
        $invoices = Invoice::all();
        return view('products.create', compact('categories', 'taxes', 'invoices'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:product_categories,id',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'invoice_id' => 'nullable|exists:invoices,id',
            'tax_id' => 'required|exists:taxes,id',
            'quantity' => 'required|integer|min:0',
        ]);

        Product::create($validatedData);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        $taxes = Tax::all();
        $invoices = Invoice::all();
        return view('products.edit', compact('product', 'categories', 'taxes', 'invoices'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:product_categories,id',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'invoice_id' => 'nullable|exists:invoices,id',
            'tax_id' => 'required|exists:taxes,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $product->update($validatedData);

        return redirect()->route('products.show', $product)->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
