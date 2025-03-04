<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'company_id' => 'required|exists:companies,id,user_id,' . Auth::id(),
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'national_id' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'position_id' => 'nullable|exists:positions,id',
            'transport_expense' => 'nullable|numeric|min:0',
            'food_expense' => 'nullable|numeric|min:0',
            'accommodation_expense' => 'nullable|numeric|min:0',
            'other_expenses' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|in:active,inactive',
        ];
    }
}
