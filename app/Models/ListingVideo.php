<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListingVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'video_path',
        'order_number',
        'main_video'
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
