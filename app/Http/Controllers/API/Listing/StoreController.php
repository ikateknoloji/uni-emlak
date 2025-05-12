<?php

namespace App\Http\Controllers\API\Listing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Listing\StoreListingRequest;
use App\Models\Listing;
use App\Models\ListingDetail;
use App\Models\ListingImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    /**
     * İlan verilerini alıp, ilgili modelleri kullanarak ilan oluşturur.
     *
     * Beklenen veri yapısı:
     *
     * {
     *     "ilan": {
     *         "deed_type_id": int,
     *         "property_type_id": int,
     *         "listing_type_id": int,
     *         "block_number": string,
     *         "parcel_number": string,
     *         "price": numeric,
     *         "square_meters": numeric,
     *         "title": string,
     *         "description": string,
     *         "neighborhood_id": int,
     *         "full_address": string,
     *         "is_loan_eligible": boolean
     *     },
     *     "details": {
     *         "content": string
     *     },
     *     "images": [
     *         {
     *             "image_path": string,
     *             "medium_image_path": string,
     *             "thumbnail_path": string,
     *             "width": int,
     *             "height": int,
     *             "main_image": boolean,
     *             "order_number": int
     *         }
     *         // ... diğer resimler
     *     ]
     * }
     *
     * @param  \App\Http\Requests\Listing\StepOneListingRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(StoreListingRequest $request): JsonResponse
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $listingData = $data['ilan'];
            $listingData['listing_date']       = now();
            $listingData['publication_status'] = 'published';
            $listingData['is_loan_eligible']   = $listingData['is_loan_eligible'] ?? false;

            $listingData['price_per_square_meter'] = $listingData['square_meters'] > 0
            ? round($listingData['price'] / $listingData['square_meters'], 2)
            : null;

            $listing = Listing::create($listingData);

            ListingDetail::create([
                'listing_id' => $listing->id,
                'content'    => $data['details']['content'] ?? '',
            ]);

            $imagesData = collect($data['images'])->map(function ($image) use ($listing) {
                return [
                    'listing_id'         => $listing->id,
                    'image_path'         => $image['image_path'],
                    'medium_image_path'  => $image['medium_image_path'],
                    'thumbnail_path'     => $image['thumbnail_path'],
                    'width'              => $image['width'],
                    'height'             => $image['height'],
                    'main_image'         => $image['main_image'],
                    'order_number'       => $image['order_number'],
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];
            })->toArray();

            ListingImage::insert($imagesData);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'İlan başarıyla oluşturuldu.',
                'data'    => $listing->load('details', 'images'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'İlan oluşturulurken bir hata oluştu.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
