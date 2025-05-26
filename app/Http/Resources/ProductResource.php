<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage; // Required if using Storage::url()

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
            'description' => $this->description,
            'price' => $this->price, // Already cast to decimal:2 in the model
            'stock' => $this->stock_quantity,
            'image_url' => $this->when($this->image_path, function () {
                // Assuming image_path stores a relative path within the storage/app/public directory
                // If you store full URLs or use a different disk, adjust accordingly.
                // return Storage::disk('public')->url($this->image_path); 
                return asset('storage/' . $this->image_path); // A common way if using public disk
            }, null),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
