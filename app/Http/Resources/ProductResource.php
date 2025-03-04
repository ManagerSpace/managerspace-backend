<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'invoice' => new InvoiceResource($this->whenLoaded('invoice')),
            'tax' => new TaxResource($this->whenLoaded('tax')),
            'category' => new ProductCategoryResource($this->whenLoaded('category')),
        ];
    }
}
