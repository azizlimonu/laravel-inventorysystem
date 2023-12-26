<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductSaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'slug' => $this->slug,
            'price' => $this->price,
            'image' => $this->image,
            'category' => Category::where('id', $this->category_id)->get()[0]['name'],
            'createdAt' => $this->created_at,
            'quantity' => $this->pivot['quantity'],
        ];
    }
}
