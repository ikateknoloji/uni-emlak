<?php

namespace App\Http\Controllers\API\Image;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\ImageHelper;
use App\Rules\IsLandscapeImage;
use Illuminate\Support\Facades\Validator;

class TemporaryListingImageController extends Controller
{
    /**
     * Geçici olarak resim yükler ve işler.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'image' => ['required', 'image', 'max:5120', new IsLandscapeImage()],
        ], [
            'image.required' => 'Resim yüklenmesi zorunludur.',
            'image.image' => 'Geçerli bir resim dosyası seçmelisiniz.',
            'image.max' => 'Resim en fazla 5MB olabilir.'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Doğrulama hatası.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $image = $request->file('image');
            $result = ImageHelper::processAndStoreTemporary($image);

            return response()->json([
                'status' => true,
                'message' => 'Resim başarıyla yüklendi.',
                'data' => $result,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Resim işlenemedi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Geçici olarak resim yükler ve sadece URL bilgisini döner.
     */
    public function storeAndReturnUrl(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'image' => ['required', 'image', 'max:5120', new IsLandscapeImage()],
        ], [
            'image.required' => 'Resim yüklenmesi zorunludur.',
            'image.image' => 'Geçerli bir resim dosyası seçmelisiniz.',
            'image.max' => 'Resim en fazla 5MB olabilir.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Doğrulama hatası.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $image = $request->file('image');
            $result = ImageHelper::processAndStoreTemporary($image);

            // Resmin tam URL'sini oluştur
            $imageUrl = asset('storage/' . $result['image_path']);

            return response()->json([
                'status' => true,
                'message' => 'Resim başarıyla yüklendi.',
                'url' => $imageUrl, // Sadece URL bilgisi döndürülüyor
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Resim işlenemedi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Geçici resmi ve kayıtlarını siler.
     */
    public function destroy(int $id): JsonResponse
    {
        $success = ImageHelper::deleteTemporary($id);

        return response()->json([
            'status' => $success,
            'message' => $success ? 'Resim silindi.' : 'Resim bulunamadı.'
        ], $success ? 200 : 404);
    }
}
