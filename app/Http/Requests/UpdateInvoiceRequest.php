<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'client_id' => 'sometimes|required|exists:clients,id',
            'project_id' => 'sometimes|nullable|exists:projects,id',
            'issue_date' => 'sometimes|required|date',
            'notes' => 'sometimes|nullable|string',
            'status' => 'sometimes|required|in:draft,sent,paid,overdue',
            'type' => 'sometimes|required|in:income,expense',
            'products' => 'sometimes|array',
            'products.*.name' => 'required_with:products|string',
            'products.*.price' => 'required_with:products|numeric|min:0',
            'products.*.quantity' => 'required_with:products|integer|min:1',
            'products.*.tax_id' => 'required_with:products|exists:taxes,id',
            'products.*.category_id' => 'required_with:products|exists:product_categories,id',
        ];
    }
}
