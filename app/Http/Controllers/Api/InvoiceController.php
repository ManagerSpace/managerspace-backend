<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Tax;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $companyId = $request->input('company_id');
            if (!$companyId) {
                return response()->json(['error' => 'Company ID is required'], 400);
            }

            $query = Invoice::where('company_id', $companyId)
                ->whereHas('company', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->with(['company', 'client', 'project']);

            if ($request->has('client')) {
                $query->whereHas('client', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->client . '%');
                });
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('date_from')) {
                $query->where('issue_date', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->where('issue_date', '<=', $request->date_to);
            }

            if ($request->has('amount_min')) {
                $query->where('total', '>=', $request->amount_min);
            }

            if ($request->has('amount_max')) {
                $query->where('total', '<=', $request->amount_max);
            }

            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            $invoices = $query->latest()->paginate(10);
        } else {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        return InvoiceResource::collection($invoices);
    }

    public function show(Invoice $invoice)
    {
        $user = Auth::user();

        if ($invoice->company->user_id === $user->id) {
            $invoice->load([
                'company',
                'client',
                'project',
                'products.tax',
                'products.category',
            ]);

            return new InvoiceResource($invoice);
        } else {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    }

    public function store(StoreInvoiceRequest $request)
    {
        $validatedData = $request->validated();
        $subtotal = 0;
        $tax_amount = 0;

        if (isset($validatedData['products']) && is_array($validatedData['products'])) {
            foreach ($validatedData['products'] as $productData) {
                $subtotal += $productData['price'] * $productData['quantity'];
                $tax = Tax::find($productData['tax_id']);
                $tax_amount += ($productData['price'] * $productData['quantity']) * ($tax->rate / 100);
            }
        } else {
            Log::error('Products array is missing or invalid in invoice creation', [
                'user_id' => $user->id,
                'validated_data' => $validatedData
            ]);
            return response()->json(['error' => 'Products data is required and must be an array'], 422);
        }

        $total = $subtotal + $tax_amount;
        $validatedData['subtotal'] = $subtotal;
        $validatedData['tax_amount'] = $tax_amount;
        $validatedData['total'] = $total;

        $invoice = Invoice::create($validatedData);

        if (isset($validatedData['products']) && is_array($validatedData['products'])) {
            foreach ($validatedData['products'] as $productData) {
                $productData['invoice_id'] = $invoice->id;
                Product::create($productData);
            }
        }

        $invoice->load(['company', 'client', 'project', 'products.category', 'products.tax']);
        return new InvoiceResource($invoice);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $validatedData = $request->validated();

        $subtotal = 0;
        $tax_amount = 0;

        if (isset($validatedData['products']) && is_array($validatedData['products'])) {
            foreach ($validatedData['products'] as $productData) {
                $subtotal += $productData['price'] * $productData['quantity'];
                $tax = Tax::find($productData['tax_id']);
                $tax_amount += ($productData['price'] * $productData['quantity']) * ($tax->rate / 100);
            }
        } else {
            $subtotal = $invoice->subtotal;
            $tax_amount = $invoice->tax_amount;
        }

        $total = $subtotal + $tax_amount;
        $validatedData['subtotal'] = $subtotal;
        $validatedData['tax_amount'] = $tax_amount;
        $validatedData['total'] = $total;
        $validatedData['status'] = (string) $validatedData['status'];

        $invoice->update($validatedData);

        if (isset($validatedData['products']) && is_array($validatedData['products'])) {
            $invoice->products()->delete();

            foreach ($validatedData['products'] as $productData) {
                $productData['invoice_id'] = $invoice->id;
                Product::create($productData);
            }
        }

        $invoice->load(['company', 'client', 'project', 'products.category', 'products.tax']);
        return new InvoiceResource($invoice);
    }

    public function destroy(Invoice $invoice)
    {
        $user = Auth::user();
        if ($user->cannot('delete', $invoice)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $invoice->delete();
        return response()->json(null, 204);
    }

    public function generatePdf(Invoice $invoice)
    {
        $user = Auth::user();
        $invoice->load(['company', 'client', 'project', 'products.category', 'products.tax']);

        if ($invoice->company->user_id != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('invoices.pdf', compact('invoice'));
        return $pdf->download('factura_' . $invoice->id . '.pdf');
    }
}
