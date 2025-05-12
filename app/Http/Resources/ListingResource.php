<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListingResource extends JsonResource
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
            'neighborhood_id'        => $this->neighborhood_id,
            'deed_type_id'           => $this->deed_type_id,
            'property_type_id'       => $this->property_type_id,
            'listing_type_id'        => $this->listing_type_id,
            'block_number'           => $this->block_number,
            'parcel_number'          => $this->parcel_number,
            'price'                  => number_format($this->price, 0, ',', '.'),
            'square_meters'          => $this->square_meters,
            'price_per_square_meter' => number_format($this->price_per_square_meter, 0, ',', '.'),
            'is_loan_eligible'       => $this->is_loan_eligible,
            'publication_status'     => $this->publication_status,
            'listing_status'         => $this->listing_status,
            'full_address'           => $this->full_address,
            'listing_date'           => $this->listing_date ? $this->listing_date->format('d.m.Y') : null,
            'update_date'            => $this->update_date ? $this->update_date->format('d.m.Y') : null,
            'details'                => new ListingDetailResource($this->whenLoaded('details')),
            'neighborhood'           => new NeighborhoodResource($this->whenLoaded('neighborhood')),
            'deed_type'              => new DeedTypeResource($this->whenLoaded('deedType')),
            'property_type'          => new PropertyTypeResource($this->whenLoaded('propertyType')),
            'listing_type'           => new ListingTypeResource($this->whenLoaded('listingType')),
            'images'                 => ListingImageResource::collection($this->whenLoaded('images')),
            'videos'                 => ListingVideoResource::collection($this->whenLoaded('videos')),
        ];
    }
}
