<?php

namespace App\Http\Controllers\API\Listing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Listing\UpdateListingStatusRequest;
use App\Models\Listing;

class ListingStatusController extends Controller
{
    public function update(UpdateListingStatusRequest $request, $id)
    {
        $listing = Listing::findOrFail($id);

        $listing->update([
            'listing_status' => $request->validated()['status'],
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'İlan durumu başarıyla güncellendi.',
            'data'    => $listing->only(['id', 'title', 'listing_status']),
        ]);
    }
}
