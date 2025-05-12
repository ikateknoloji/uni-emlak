<?php

namespace App\Http\Controllers\API\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\DeedType;
use App\Models\PropertyType;
use App\Models\ListingType;

class MetadataController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return response()->json([
            'provinces' => Province::select('id', 'name', 'slug')->orderBy('name')->get(),
            'deed_types' => DeedType::select('id', 'name', 'slug')->get(),
            'property_types' => PropertyType::select('id', 'name', 'slug')->get(),
            'listing_types' => ListingType::select('id', 'name', 'slug')->get(),
        ]);
    }
}
