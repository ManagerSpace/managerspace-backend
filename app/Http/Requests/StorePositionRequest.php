<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePositionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:positions,name,' . $this->route('position'),
            'description' => 'nullable|string|max:500',
            'base_salary' => 'required|numeric|min:0',
            'level' => 'required|in:Junior,Mid-Level,Senior',
            'department' => 'required|string|max:255',
        ];
    }
}
