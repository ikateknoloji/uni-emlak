<?php

namespace App\Http\Controllers\API\listing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Listing\StepOneListingRequest;
use App\Http\Requests\Listing\StepTwoListingRequest;
use Illuminate\Http\JsonResponse;

class ValidationController extends Controller
{
   /**
     * İlk adım doğrulamasını gerçekleştirir.
     *
     * @param  \App\Http\Requests\Listing\StepOneListingRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateStepOne(StepOneListingRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        return response()->json([
            'status'  => true,
            'message' => 'İlk adım verileri doğrulandı.',
            'data'    => $validatedData,
        ]);
    }

    /**
     * İkinci adım doğrulamasını gerçekleştirir.
     *
     * @param  \App\Http\Requests\Listing\StepTwoListingRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateStepTwo(StepTwoListingRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        return response()->json([
            'status'  => true,
            'message' => 'İkinci adım verileri doğrulandı.',
            'data'    => $validatedData,
        ]);
    }
}
