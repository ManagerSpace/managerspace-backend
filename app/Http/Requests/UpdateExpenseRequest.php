<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category_id' => 'sometimes|required|exists:expense_categories,id',
            'id_company' => 'sometimes|required|exists:companies,id',
            'amount' => 'sometimes|required|numeric|min:0',
            'date' => 'sometimes|required|date',
            'description' => 'nullable|string|max:255',
            'is_recurring' => 'sometimes|boolean',
            'recurrence_frequency' => 'required_if:is_recurring,true|in:daily,weekly,monthly,yearly',
            'tax_deductible' => 'sometimes|boolean',
        ];
    }
}
