<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class CardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'listing_number'         => $this->listing_number,
            'title'                  => $this->title,
            'description'            => $this->description,
            'price'                  => number_format($this->price, 0, ',', '.'),
            'square_meters'          => $this->square_meters,
            'price_per_square_meter' => $this->price_per_square_meter,
            'full_address'           => $this->full_address,
            'listing_date'           => $this->listing_date ? Carbon::parse($this->listing_date)->format('d-m-Y') : null,
            'neighborhood'           => new NeighborhoodResource($this->whenLoaded('neighborhood')),
            'listing_type' => $this->whenLoaded('listingType', fn() => $this->listingType->name),
            'property_type' => $this->whenLoaded('propertyType', fn() => $this->propertyType->name),
            'primary_image' => $this->whenLoaded('images', function () {
                return new ListingImageResource(
                    $this->images->firstWhere('main_image', true) ?? $this->images->first()
                );
            }),
        ];
    }
}
