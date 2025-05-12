<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NeighborhoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'province_id'     => $this->province_id,
            'district_id'     => $this->district_id,
            'subdistrict_id'  => $this->subdistrict_id,
            'name'            => $this->name,
            'postal_code'     => $this->postal_code,
            'content'         => $this->full_location,
        ];
    }
}
