<?php

namespace App\Http\Controllers\API\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\CommonTypeRequest;
use App\Models\ListingType;
use Illuminate\Support\Str;

class ListingTypeController extends Controller
{
    public function index()
    {
        return response()->json(ListingType::all());
    }

    public function store(CommonTypeRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $listingType = ListingType::create($data);

        return response()->json($listingType, 201);
    }

    public function show(ListingType $listingType)
    {
        return response()->json($listingType);
    }

    public function update(CommonTypeRequest $request, ListingType $listingType)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $listingType->update($data);

        return response()->json($listingType);
    }

    public function destroy(ListingType $listingType)
    {
        $listingType->delete();

        return response()->json(['message' => 'İlan türü başarıyla silindi.']);
    }
}

