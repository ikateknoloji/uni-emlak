<?php

namespace App\Http\Controllers\API\listing;

use App\Http\Controllers\Controller;
use App\Http\Resources\ListingResource;
use App\Models\Listing;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Resources\CardResource;
use Illuminate\Support\Carbon;

class IndexListingController extends Controller
{
    public function index(Request $request)
    {
        $listings = QueryBuilder::for(Listing::class)
            ->allowedIncludes([
                'neighborhood.province',
                'neighborhood.district',
                'neighborhood.subdistrict',
                'listingType',
                'propertyType',
                'deedType',
                'images',
                'neighborhood'
            ])
            ->allowedFilters([
                'title',
                // 1) Mahalle filtresi her zaman en öncelikli
                AllowedFilter::callback('neighborhood_id', function ($q, $v) {
                    $q->where('neighborhood_id', $v);
                }),

                // 2) Sadece mahalle gelmemişse ilçe çalışır
                AllowedFilter::callback('district_id', function ($q, $v) {
                    if (request('filter.neighborhood_id')) return;
                    $q->whereHas(
                        'neighborhood',
                        fn($q2) =>
                        $q2->where('district_id', $v)
                    );
                }),

                // 3) Sadece mahalle ve ilçe gelmemişse il çalışır
                AllowedFilter::callback('province_id', function ($q, $v) {
                    if (request('filter.neighborhood_id') || request('filter.district_id')) return;
                    $q->whereHas(
                        'neighborhood',
                        fn($q2) =>
                        $q2->where('province_id', $v)
                    );
                }),
                AllowedFilter::exact('listing_status'),

                AllowedFilter::exact('publication_status'),

                AllowedFilter::callback('price_min', fn($q, $v) => $q->where('price', '>=', $v)),

                AllowedFilter::callback('price_max', fn($q, $v) => $q->where('price', '<=', $v)),

                AllowedFilter::callback('square_meters_min', fn($q, $v) => $q->where('square_meters', '>=', $v)),

                AllowedFilter::callback('square_meters_max', fn($q, $v) => $q->where('square_meters', '<=', $v)),

                AllowedFilter::callback('listing_date_min', fn($q, $v) => $q->whereDate('listing_date', '>=', $v)),

                AllowedFilter::callback('listing_date_max', fn($q, $v) => $q->whereDate('listing_date', '<=', $v)),

                AllowedFilter::callback('listing_date', function ($query, $value) {
                    if (!$value || $value === 'tumu') {
                        return;
                    }

                    $start = match ($value) {
                        'bugun'       => Carbon::today(),
                        'son-3-gun'   => Carbon::today()->subDays(3),
                        'son-1-hafta' => Carbon::today()->subWeek(),
                        'son-15-gun'  => Carbon::today()->subDays(15),
                        'son-1-ay'    => Carbon::today()->subMonth(),
                        'son-2-ay'    => Carbon::today()->subMonths(2),
                        default       => null,
                    };

                    if ($start) {
                        $query->whereDate('listing_date', '>=', $start);
                    }
                }),

                AllowedFilter::callback('city', function ($query, $value) {
                    $query->whereHas('neighborhood', function ($q) use ($value) {
                        $q->whereHas('province', function ($q2) use ($value) {
                            $q2->where('slug', 'like', '%' . $value . '%');
                        });
                    });
                }),

                AllowedFilter::callback('listing_type', fn($q, $v) => $q->whereHas('listingType', fn($q2) => $q2->where('slug', 'like', '%' . $v . '%'))),

                AllowedFilter::callback(
                    'property_type',
                    fn($q, $v) => $q->whereHas('propertyType', fn($q2) => $q2->where('slug', 'like', '%' . $v . '%'))
                ),

                AllowedFilter::callback('deedType', fn($q, $v) =>  $q->whereHas(
                    'deedType',
                    fn($q2) => $q2->where('slug', 'like', "%{$v}%")
                )),

                AllowedFilter::callback('credit_eligibility', function ($query, $value) {
                    if ($value === '') {
                        return;
                    }
                    if ($value === 'uygun') {
                        $query->where('is_loan_eligible', true);
                    } elseif ($value === 'uygun-degil') {
                        $query->where('is_loan_eligible', false);
                    }
                }),
            ])
            ->allowedSorts(['price', 'listing_date', 'square_meters'])
            ->with(['images', 'neighborhood', 'listingType', 'propertyType'])
            ->paginate($request->get('per_page', 10))
            ->appends($request->query());

        return CardResource::collection($listings);
    }

    /**
     * Belirtilen ID’ye ait ilanı getirir.
     *
     * @param int $id
     * @return \App\Http\Resources\ListingResource
     */
    public function show($id)
    {
        $listing = Listing::with([
            'neighborhood.province',
            'neighborhood.district',
            'neighborhood.subdistrict',
            'listingType',
            'propertyType',
            'deedType',
            'images',
            'videos'
        ])->findOrFail($id);

        return new ListingResource($listing);
    }

    /**
     * Belirtilen ilan numarasına göre ilanı getirir.
     *
     * @param string $listingNumber
     * @return \App\Http\Resources\ListingResource
     */
    public function showByListingNumber($listingNumber)
    {
        $listing = Listing::with([
            'neighborhood.province',
            'neighborhood.district',
            'neighborhood.subdistrict',
            'listingType',
            'propertyType',
            'deedType',
            'images',
            'videos',
            'details'
        ])->where('listing_number', $listingNumber)->firstOrFail();

        return new ListingResource($listing);
    }
}
