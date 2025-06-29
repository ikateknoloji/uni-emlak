<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }
}
