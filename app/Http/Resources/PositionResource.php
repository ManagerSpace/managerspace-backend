<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PositionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'base_salary' => $this->base_salary,
            'level' => $this->level,
            'department' => $this->department,
            'employees_count' => $this->employees()->count(),
        ];
    }
}
