<?php

namespace App\Http\Resources\Configuration;

use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigurationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'product' => new ProductResource($this->whenLoaded('product')),
            'rule_type' => $this->rule_type,
            'rule_details' => json_decode($this->rule_details),
            'created_at' => $this->created_at,
            'active' => $this->when($this->active, true),
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
