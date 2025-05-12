<?php

namespace App\Http\Controllers\API\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\CommonTypeRequest;
use App\Models\DeedType;
use Illuminate\Support\Str;

class DeedTypeController extends Controller
{
    public function index()
    {
        return response()->json(DeedType::all());
    }

    public function store(CommonTypeRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $deedType = DeedType::create($data);

        return response()->json($deedType, 201);
    }

    public function show(DeedType $deedType)
    {
        return response()->json($deedType);
    }

    public function update(CommonTypeRequest $request, DeedType $deedType)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $deedType->update($data);

        return response()->json($deedType);
    }

    public function destroy(DeedType $deedType)
    {
        $deedType->delete();

        return response()->json(['message' => 'Tapu türü başarıyla silindi.']);
    }
}
