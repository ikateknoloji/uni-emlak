<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListingDetail extends Model
{
    use HasFactory;

    protected $fillable = ['listing_id', 'content'];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
