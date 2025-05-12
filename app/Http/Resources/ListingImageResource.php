<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListingImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'listing_id'        => $this->listing_id,
            'image_path'        => asset('storage/' . $this->image_path),
            'medium_image_path' => asset('storage/' . $this->medium_image_path),
            'thumbnail_path'    => asset('storage/' . $this->thumbnail_path),
            'width'             => $this->width,
            'height'            => $this->height,
            'main_image'        => $this->main_image,
            'order_number'      => $this->order_number,
        ];
    }
}
