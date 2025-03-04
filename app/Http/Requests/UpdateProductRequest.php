<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'invoice_id' => 'sometimes|required|exists:invoices,id',
            'tax_id' => 'sometimes|required|exists:taxes,id',
            'quantity' => 'sometimes|required|integer|min:1',
            'category_id' => 'sometimes|required|exists:product_categories,id',
        ];
    }
}
