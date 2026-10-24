<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'product',
            'id' => $this->id,
            'attributes' => [
                'product' => $this->product,
                'description' => $this->when(
                    ! $request->routeIs(['products.index', 'users.products.index']),
                    $this->description
                ),
                'status' => $this->status,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            'relationships' => [
                'author' => [
                    'type' => 'user',
                    'id' => $this->user_id,
                ],
            ],
            'links' => [
                'self' => '/api/v1/products/' . $this->id,
            ],
        ];
    }
}