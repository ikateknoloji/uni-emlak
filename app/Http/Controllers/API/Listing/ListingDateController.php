<?php

namespace App\Http\Controllers\API\Listing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Listing;
use Illuminate\Http\JsonResponse;

class ListingDateController extends Controller
{
    public function update($id): JsonResponse
    {
        $listing = Listing::findOrFail($id);

        $listing->update([
            'listing_date' => now(),
            'update_date' => now(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'İlan tarihi bugünün tarihi olarak güncellendi.',
            'data'    => $listing->only(['id', 'title', 'listing_date']),
        ]);
    }
}
