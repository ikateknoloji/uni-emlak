<?php

namespace App\Http\Controllers\API\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;

class DistrictController extends Controller
{
    public function __invoke($provinceId)
    {
        $districts = District::where('province_id', $provinceId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($districts);
    }
}
