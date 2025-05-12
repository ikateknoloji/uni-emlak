<?php

namespace App\Http\Controllers\API\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Neighborhood;

class NeighborhoodController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($districtId)
    {
        $neighborhoods = Neighborhood::where('district_id', $districtId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($neighborhoods);
    }
}
