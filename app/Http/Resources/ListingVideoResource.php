<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListingVideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'listing_id'   => $this->listing_id,
            'video_path'   => $this->video_path,
            'order_number' => $this->order_number,
            'main_video'   => $this->main_video,
        ];
    }
}
