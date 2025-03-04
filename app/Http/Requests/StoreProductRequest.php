<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'invoice_id' => 'required|exists:invoices,id',
            'tax_id' => 'required|exists:taxes,id',
            'quantity' => 'required|integer|min:1',
            'category_id' => 'required|exists:product_categories,id',
        ];
    }
}
