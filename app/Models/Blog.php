<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    /**
     * Modelde toplu atamaya izin verilen alanlar.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'image_path',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_author',
    ];
}
