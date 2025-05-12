<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YoutubeVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'thumbnail',
        'listing_id',
    ];

    /**
     * Define a relationship with the Listing model.
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
