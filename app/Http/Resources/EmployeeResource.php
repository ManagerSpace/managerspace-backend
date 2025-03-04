<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'national_id' => $this->national_id,
            'phone' => $this->phone,
            'address' => $this->address,
            'hire_date' => $this->hire_date,
            'status' => $this->status,
            'transport_expense' => $this->transport_expense,
            'food_expense' => $this->food_expense,
            'accommodation_expense' => $this->accommodation_expense,
            'other_expenses' => $this->other_expenses,
            'position' => new PositionResource($this->whenLoaded('position')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
