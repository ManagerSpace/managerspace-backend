<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'company_id' => 'nullable|exists:companies,id',
            'issue_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,sent,paid,overdue',
            'type' => 'required|in:income,expense',
            'products' => 'required|array',
            'products.*.name' => 'required|string',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.tax_id' => 'required|exists:taxes,id',
            'products.*.category_id' => 'required|exists:product_categories,id',
        ];
    }
}
