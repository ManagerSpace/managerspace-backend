<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $company = $this->route('company');
        $id = $company ? $company->id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:companies,email,' . $id,
            'website' => 'nullable|url|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The company name is required.',
            'email.required' => 'The company email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'website.url' => 'Please enter a valid website URL.',
        ];
    }
}
