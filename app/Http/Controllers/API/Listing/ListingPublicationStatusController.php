<?php

namespace App\Http\Controllers\API\Listing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Listing\UpdatePublicationStatusRequest;
use App\Models\Listing;

class ListingPublicationStatusController extends Controller
{
    public function update(UpdatePublicationStatusRequest $request, $id)
    {
        $listing = Listing::findOrFail($id);

        $listing->update([
            'publication_status' => $request->validated()['status'],
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Yayın durumu başarıyla güncellendi.',
            'data'    => $listing->only(['id', 'title', 'publication_status']),
        ]);
    }
}
