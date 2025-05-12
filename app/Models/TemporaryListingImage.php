<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryListingImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'medium_image_path',
        'thumbnail_path',
    ];
}
