<?php

namespace App\Http\Controllers\API\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\CommonTypeRequest;
use App\Models\PropertyType;
use Illuminate\Support\Str;

class PropertyTypeController extends Controller
{
    public function index()
    {
        return response()->json(PropertyType::all());
    }

    public function store(CommonTypeRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $propertyType = PropertyType::create($data);

        return response()->json($propertyType, 201);
    }

    public function show(PropertyType $propertyType)
    {
        return response()->json($propertyType);
    }

    public function update(CommonTypeRequest $request, PropertyType $propertyType)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $propertyType->update($data);

        return response()->json($propertyType);
    }

    public function destroy(PropertyType $propertyType)
    {
        $propertyType->delete();

        return response()->json(['message' => 'Mülk tipi başarıyla silindi.']);
    }
}
