<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category_id' => 'required|exists:expense_categories,id',
            'id_company' => 'required|exists:companies,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'is_recurring' => 'boolean',
            'recurrence_frequency' => 'required_if:is_recurring,true|in:daily,weekly,monthly,yearly',
            'tax_deductible' => 'boolean',
        ];
    }
}
