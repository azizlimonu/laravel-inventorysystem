<?php

namespace App\Http\Resources;

use App\Models\ProductSale;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{

    public static $wrap = 'sale';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client' => $this->client,
            'total' => $this->total,
            'products' => new ProductSaleCollection(Sale::find($this->id)->products),
            // 'products' => Sale::find($this->id)->products,
            
            'createdAt' => $this->created_at,
        ];
    }
}
