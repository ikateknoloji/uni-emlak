<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_number',
        'title',
        'description',
        'neighborhood_id',
        'deed_type_id',
        'property_type_id',
        'listing_type_id',
        'block_number',
        'parcel_number',
        'price',
        'square_meters',
        'price_per_square_meter',
        'is_loan_eligible',
        'publication_status',
        'listing_status',
        'full_address',
        'listing_date',
        'update_date'
    ];

    protected $casts = [
        'is_loan_eligible' => 'boolean',
        'listing_date' => 'date',
        'update_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($listing) {
            if (empty($listing->listing_number)) {
                $listing->listing_number = self::generateUniqueListingNumber();
            }
        });
    }

    public static function generateUniqueListingNumber(): string
    {
        do {
            $number = rand(100000, 999999) . '-' . rand(1000, 9999);
        } while (self::where('listing_number', $number)->exists());

        return $number;
    }


    public function getFullLocationAttribute()
    {
        return "{$this->neighborhood->province->name}, {$this->neighborhood->district->name}, {$this->neighborhood->name}";
    }


    public function neighborhood()
    {
        return $this->belongsTo(Neighborhood::class);
    }

    public function province()
    {
        return $this->hasOneThrough(Province::class, Neighborhood::class);
    }

    public function district()
    {
        return $this->hasOneThrough(District::class, Neighborhood::class);
    }

    public function subdistrict()
    {
        return $this->hasOneThrough(Subdistrict::class, Neighborhood::class);
    }

    public function deedType()
    {
        return $this->belongsTo(DeedType::class);
    }

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function listingType()
    {
        return $this->belongsTo(ListingType::class);
    }

    public function details()
    {
        return $this->hasOne(ListingDetail::class);
    }

    public function images()
    {
        return $this->hasMany(ListingImage::class);
    }

    public function videos()
    {
        return $this->hasMany(ListingVideo::class);
    }

    public function youtubeVideos()
    {
        return $this->hasMany(YoutubeVideo::class);
    }

    public function location()
    {
        return $this->hasOne(Location::class);
    }
}
