<?php

namespace App\Http\Controllers\API\Update;

use App\Http\Controllers\Controller;
use App\Http\Requests\Update\UpdateListingRequest;
use App\Models\Listing;
use Illuminate\Http\JsonResponse;

class UpdateListingController extends Controller
{
    public function __invoke(UpdateListingRequest $request, $id): JsonResponse
    {
        $listing = Listing::findOrFail($id);

        $data = $request->validated();

        $data['update_date'] = now();
        $data['price_per_square_meter'] = $data['square_meters'] > 0
            ? round($data['price'] / $data['square_meters'], 2)
            : null;

        $listing->update($data);

        return response()->json([
            'status'  => true,
            'message' => 'İlan bilgileri başarıyla güncellendi.',
            'data'    => $listing,
        ]);
    }
}