<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
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

    public function messages()
    {
        return [
            'first_name.string' => 'The first name must be a string.',
            'last_name.string' => 'The last name must be a string.',
            'birth_date.date' => 'The birth date must be a valid date.',
            'gender.in' => 'The gender must be either male, female, or other.',
            'national_id.string' => 'The national ID must be a string.',
            'phone.string' => 'The phone number must be a string.',
            'email.email' => 'The email must be a valid email address.',
            'position_id.exists' => 'The position must exist in the positions table.',
            'status.in' => 'The status must be either active or inactive.',
            'transport_expense.numeric' => 'The transport expense must be a numeric value.',
            'food_expense.numeric' => 'The food expense must be a numeric value.',
            'accommodation_expense.numeric' => 'The accommodation expense must be a numeric value.',
            'other_expenses.numeric' => 'The other expenses must be a numeric value.',
        ];
    }
}
