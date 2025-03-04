<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Company;
use App\Models\Client;
use App\Models\Project;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Tax;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['company', 'client', 'project'])->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $companies = Company::all();
        $clients = Client::all();
        $projects = Project::all();
        $categories = ProductCategory::all();
        $taxes = Tax::all();
        return view('invoices.create', compact('companies', 'clients', 'projects', 'categories', 'taxes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'issue_date' => 'required|date',
            'status' => 'required|in:draft,pending,paid',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.category_id' => 'required|exists:product_categories,id',
            'products.*.name' => 'required|string',
            'products.*.description' => 'nullable|string',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.tax_id' => 'required|exists:taxes,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $subtotal = 0;
        $tax_amount = 0;

        foreach ($request->products as $productData) {
            $subtotal += $productData['price'] * $productData['quantity'];
            $tax = Tax::find($productData['tax_id']);
            $tax_amount += ($productData['price'] * $productData['quantity']) * ($tax->rate / 100);
        }

        $total = $subtotal + $tax_amount;

        $validatedData['subtotal'] = $subtotal;
        $validatedData['tax_amount'] = $tax_amount;
        $validatedData['total'] = $total;

        $invoice = Invoice::create($validatedData);

        foreach ($request->products as $productData) {
            $productData['invoice_id'] = $invoice->id;
            Product::create($productData);
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['company', 'client', 'project', 'products.category', 'products.tax']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $companies = Company::all();
        $clients = Client::all();
        $projects = Project::all();
        $categories = ProductCategory::all();
        $taxes = Tax::all();
        return view('invoices.edit', compact('invoice', 'companies', 'clients', 'projects', 'categories', 'taxes'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validatedData = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'issue_date' => 'required|date',
            'status' => 'required|string|in:draft,pending,paid',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.category_id' => 'required|exists:product_categories,id',
            'products.*.name' => 'required|string',
            'products.*.description' => 'nullable|string',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.tax_id' => 'required|exists:taxes,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $subtotal = 0;
        $tax_amount = 0;

        foreach ($request->products as $productData) {
            $subtotal += $productData['price'] * $productData['quantity'];
            $tax = Tax::find($productData['tax_id']);
            $tax_amount += ($productData['price'] * $productData['quantity']) * ($tax->rate / 100);
        }

        $total = $subtotal + $tax_amount;
        $validatedData['subtotal'] = $subtotal;
        $validatedData['tax_amount'] = $tax_amount;
        $validatedData['total'] = $total;
        $validatedData['status'] = (string) $validatedData['status'];

        $invoice->update($validatedData);
        $invoice->products()->delete();

        foreach ($request->products as $productData) {
            $productData['invoice_id'] = $invoice->id;
            Product::create($productData);
        }

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    public function generatePdf(Invoice $invoice)
    {
        $invoice->load(['company', 'client', 'project', 'products.category', 'products.tax']);
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download('factura_' . $invoice->id . '.pdf');
    }
}
