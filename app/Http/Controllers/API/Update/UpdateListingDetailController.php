<?php

namespace App\Http\Controllers\API\Update;

use App\Http\Controllers\Controller;
use App\Models\ListingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Rules\ContainsHtml;

class UpdateListingDetailController extends Controller
{
    public function __invoke(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'content' => ['required', 'string', new ContainsHtml()],
        ], [
            'content.required' => 'Açıklama alanı zorunludur.',
            'content.string'   => 'Açıklama metin formatında olmalıdır.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Doğrulama hatası oluştu.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $detail = ListingDetail::where('listing_id', $id)->firstOrFail();
        $detail->update(['content' => $request->input('content')]);

        return response()->json([
            'status'  => true,
            'message' => 'İlan açıklaması başarıyla güncellendi.',
            'data'    => $detail,
        ]);
    }
}
