<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IncomeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'date' => $this->date->format('Y-m-d'),
            'description' => $this->description,
            'is_recurring' => $this->is_recurring,
            'recurrence_frequency' => $this->recurrence_frequency,
            'tax_deductible' => $this->tax_deductible,
            'category' => new IncomeCategoryResource($this->whenLoaded('category')),
            'company' => new CompanyResource($this->whenLoaded('company')),
        ];
    }
}
