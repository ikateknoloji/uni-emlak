<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /** @use HasFactory<\Database\Factories\LocationFactory> */
    use HasFactory;

    protected $fillable = [
        'location_url',
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
