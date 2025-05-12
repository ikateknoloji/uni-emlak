<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Common\MetadataController;
use App\Http\Controllers\API\Common\DistrictController;
use App\Http\Controllers\API\Common\NeighborhoodController;
use App\Http\Controllers\API\Common\PropertyTypeController;
use App\Http\Controllers\API\Common\ListingTypeController;
use App\Http\Controllers\API\Common\DeedTypeController;
use App\Http\Controllers\API\Listing\IndexListingController;
use App\Http\Controllers\API\Listing\ValidationController;
use App\Http\Controllers\API\Listing\StoreController;
use App\Http\Controllers\API\Listing\ListingStatusController;
use App\Http\Controllers\API\Listing\ListingPublicationStatusController;
use App\Http\Controllers\API\Listing\ListingDateController;
use App\Http\Controllers\API\Image\TemporaryListingImageController;
use App\Http\Controllers\API\Image\ListingImageController;
use App\Http\Controllers\API\Update\UpdateListingController;
use App\Http\Controllers\API\Update\UpdateListingDetailController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\UserController;
use App\Http\Controllers\API\Blog\BlogController;

Route::get('/blogs/{slug}', [BlogController::class, 'show']);
Route::get('/blogs', [BlogController::class, 'index']);

Route::middleware(['web', 'auth:sanctum', 'role:admin,user'])->group(function () {
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::put('/blogs/{id}', [BlogController::class, 'update']);
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);
});


Route::middleware(['web', 'auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/users', [UserController::class, 'getAllUsers']);
    Route::patch('/update-role/{id}', [UserController::class, 'updateRole']);
    Route::patch('/reset-password/{id}', [UserController::class, 'resetPassword']);
    Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser']);
});


// Metadata
Route::get('/metadata', MetadataController::class);
Route::get('/districts/{provinceId}', DistrictController::class);
Route::get('/neighborhoods/{districtId}', NeighborhoodController::class);

// Common Types
Route::middleware(['web', 'auth:sanctum', 'role:admin,user'])->group(
    function () {
        Route::prefix('common')->group(function () {
            Route::apiResource('listing-types', ListingTypeController::class);
            Route::apiResource('property-types', PropertyTypeController::class);
            Route::apiResource('deed-types', DeedTypeController::class);
        });
    }
);

// Listings
Route::prefix('listings')->group(function () {
    Route::get('', [IndexListingController::class, 'index']);
    Route::get('/{id}', [IndexListingController::class, 'show']);
    Route::get('/ilan/{listingNumber}', [IndexListingController::class, 'showByListingNumber']);

    Route::middleware(['web', 'auth:sanctum', 'role:admin,user'])->group(function () {
        Route::post('/validation/step-one', [ValidationController::class, 'validateStepOne']);
        Route::post('/validation/step-two', [ValidationController::class, 'validateStepTwo']);
        Route::post('/create', StoreController::class);
        Route::patch('/{id}/listing-status', [ListingStatusController::class, 'update']);
        Route::patch('/{id}/publication-status', [ListingPublicationStatusController::class, 'update']);
        Route::patch('/{id}/listing-date', [ListingDateController::class, 'update']);
        Route::put('/{id}', UpdateListingController::class);
        Route::put('/{id}/detail', UpdateListingDetailController::class);
    });
});


Route::post('/temporary-images', [TemporaryListingImageController::class, 'store']);
Route::post('/add-blog-image', [TemporaryListingImageController::class, 'storeAndReturnUrl']);
Route::delete('/temporary-images/{id}', [TemporaryListingImageController::class, 'destroy']);

Route::middleware(['web', 'auth:sanctum', 'role:admin,user'])->group(function () {
    Route::post('/listing-images', [ListingImageController::class, 'store']);
    Route::put('/listing-images/{id}', [ListingImageController::class, 'update']);
    Route::delete('/listing-images/{id}', [ListingImageController::class, 'destroy']);
});
