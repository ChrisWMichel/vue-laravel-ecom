<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'qty' => $this->qty,
            'desc' => $this->desc,
            'price' => $this->price,
            //'image' => $this->image,
            'category' => $this->category,
            'brand' => $this->brand,
            'colors' => $this->colors,
            'sizes' => $this->sizes,
            'reviews' => $this->reviews,
            'status' => $this->status,
            'thumbnail' => $this->thumbnail,
            // 'first_image' => $this->first_image ? asset($this->first_image) : null,
            // 'second_image' => $this->second_image ? asset($this->second_image) : null,
            // 'third_image' => $this->third_image ? asset($this->third_image) : null,
            'first_image' => $this->first_image,
            'second_image' => $this->second_image,
            'third_image' => $this->third_image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
