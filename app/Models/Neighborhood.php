<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Neighborhood extends Model
{
    use HasFactory;

    protected $fillable = ['province_id', 'district_id', 'subdistrict_id', 'name', 'postal_code'];

    public function getFullLocationAttribute(): string
    {
        $provinceName = $this->province->name ?? '';
        $districtName = $this->district->name ?? '';
        // Mahallenin adı zaten $this->name
        return trim("{$provinceName} / {$districtName} / {$this->name}", ' /');;
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class);
    }

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }
}
