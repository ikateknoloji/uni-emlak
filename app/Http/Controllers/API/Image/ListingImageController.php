<?php

namespace App\Http\Controllers\API\Image;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Listing\ListingImageUpdateRequest;
use App\Http\Requests\Listing\ListingImageStoreRequest;
use App\Models\ListingImage;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ListingImageResource;


class ListingImageController extends Controller
{
    public function update(ListingImageUpdateRequest $request, $id)
    {
        $validated = $request->validated();

        $image = ListingImage::findOrFail($id);

        // Eski dosyaları sil
        $disk = Storage::disk('public');
        foreach (
            [
                $image->image_path,
                $image->medium_image_path,
                $image->thumbnail_path
            ] as $oldPath
        ) {
            if ($disk->exists($oldPath)) {
                $disk->delete($oldPath);
            }
        }

        $image->update([
            'image_path'         => $validated['image_path'],
            'medium_image_path'  => $validated['medium_image_path'],
            'thumbnail_path'     => $validated['thumbnail_path'],
            'width'              => $validated['width'],
            'height'             => $validated['height'],
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Resim başarıyla güncellendi.',
            'data'    => $image,
        ]);
    }

    public function store(ListingImageStoreRequest $request)
    {
        $data = $request->validated();

        // Sıralama numarasını belirle
        $maxOrder = ListingImage::where('listing_id', $data['listing_id'])->max('order_number') ?? 0;

        $image = ListingImage::create([
            'listing_id'         => $data['listing_id'],
            'image_path'         => $data['image_path'],
            'medium_image_path'  => $data['medium_image_path'],
            'thumbnail_path'     => $data['thumbnail_path'],
            'width'              => $data['width'],
            'height'             => $data['height'],
            'order_number'       => $maxOrder + 1,
            'main_image'         => false,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Resim başarıyla eklendi.',
            'data' => new ListingImageResource($image),
        ], 201);
    }

    public function destroy($id)
    {
        $image = ListingImage::findOrFail($id);

        // Disk üzerinden dosyaları sil
        $disk = Storage::disk('public');

        foreach (
            [
                $image->image_path,
                $image->medium_image_path,
                $image->thumbnail_path
            ] as $path
        ) {
            if ($disk->exists($path)) {
                $disk->delete($path);
            }
        }

        // DB kaydını sil
        $image->delete();

        return response()->json([
            'status'  => true,
            'message' => 'İlan resmi başarıyla silindi.',
        ]);
    }
}
