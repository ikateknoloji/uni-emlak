<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::paginate(12);

        return response()->json([
            'data' => $blogs->items(),
            'meta' => [
                'current_page' => $blogs->currentPage(),
                'from' => $blogs->firstItem(),
                'last_page' => $blogs->lastPage(),
                'links' => $blogs->linkCollection(),
                'path' => $blogs->path(),
                'per_page' => $blogs->perPage(),
                'to' => $blogs->lastItem(),
                'total' => $blogs->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasyon kuralları
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:blogs,title',
            'description' => 'required|string|max:500',
            'content' => 'required|string|max:4000',
            'image_path' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_author' => 'nullable|string|max:255',
        ], [
            'title.unique' => 'Bu başlığa sahip bir blog yazısı bulunmaktadır.',
            'title.required' => 'Başlık alanı zorunludur.',
            'title.string' => 'Başlık bir metin olmalıdır.',
            'title.max' => 'Başlık en fazla 255 karakter olabilir.',
            'description.string' => 'Açıklama bir metin olmalıdır.',
            'description.required' => 'Açıklama alanı zorunludur.',
            'description.max' => 'Açıklama en fazla 500 karakter olabilir.',
            'content.required' => 'İçerik alanı zorunludur.',
            'content.string' => 'İçerik bir metin olmalıdır.',
            'content.max' => 'İçerik en fazla 4000 karakter olabilir.',
            'image_path.required' => 'Resim yolu alanı zorunludur.',
            'image_path.string' => 'Resim yolu bir metin olmalıdır.',
            'meta_title.string' => 'Meta başlık bir metin olmalıdır.',
            'meta_title.max' => 'Meta başlık en fazla 255 karakter olabilir.',
            'meta_description.string' => 'Meta açıklama bir metin olmalıdır.',
            'meta_description.max' => 'Meta açıklama en fazla 255 karakter olabilir.',
            'meta_keywords.string' => 'Meta anahtar kelimeler bir metin olmalıdır.',
            'meta_keywords.max' => 'Meta anahtar kelimeler en fazla 255 karakter olabilir.',
            'meta_author.string' => 'Meta yazar bir metin olmalıdır.',
            'meta_author.max' => 'Meta yazar en fazla 255 karakter olabilir.',
        ]);

        // Validasyon hatalarını kontrol et
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Türkçe karakterleri destekleyen slug oluştur
        $slug = Str::slug(str_replace(
            ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'],
            ['i', 'g', 'u', 's', 'o', 'c', 'I', 'G', 'U', 'S', 'O', 'C'],
            $request->title
        ), '-');

        // Blog kaydını oluştur
        $blog = Blog::create([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'content' => $request->content,
            'image_path' => $request->image_path,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'meta_author' => $request->meta_author,
        ]);

        // Başarılı yanıt döndür
        return response()->json([
            'success' => true,
            'message' => 'Blog başarıyla oluşturuldu.',
            'data' => $blog,
        ], 201);
    }

    /**
     * Display the specified resource by slug.
     */
    public function show(string $slug)
    {
        $blog = Blog::where('slug', $slug)->first();

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog bulunamadı.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $blog,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Blog kaydını bul
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog bulunamadı.',
            ], 404);
        }

        // Validasyon kuralları
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'image_path' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_author' => 'nullable|string|max:255',
        ], [
            'title.required' => 'Başlık alanı zorunludur.',
            'title.string' => 'Başlık bir metin olmalıdır.',
            'title.max' => 'Başlık en fazla 255 karakter olabilir.',
            'description.string' => 'Açıklama bir metin olmalıdır.',
            'content.required' => 'İçerik alanı zorunludur.',
            'content.string' => 'İçerik bir metin olmalıdır.',
            'image_path.required' => 'Resim yolu alanı zorunludur.',
            'image_path.string' => 'Resim yolu bir metin olmalıdır.',
            'meta_title.string' => 'Meta başlık bir metin olmalıdır.',
            'meta_title.max' => 'Meta başlık en fazla 255 karakter olabilir.',
            'meta_description.string' => 'Meta açıklama bir metin olmalıdır.',
            'meta_keywords.string' => 'Meta anahtar kelimeler bir metin olmalıdır.',
            'meta_author.string' => 'Meta yazar bir metin olmalıdır.',
            'meta_author.max' => 'Meta yazar en fazla 255 karakter olabilir.',
        ]);

        // Validasyon hatalarını kontrol et
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Türkçe karakterleri destekleyen slug oluştur
        $slug = Str::slug(str_replace(
            ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'],
            ['i', 'g', 'u', 's', 'o', 'c', 'I', 'G', 'U', 'S', 'O', 'C'],
            $request->title
        ), '-');

        // Blog kaydını güncelle
        $blog->update([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'content' => $request->content,
            'image_path' => $request->image_path,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'meta_author' => $request->meta_author,
        ]);

        // Başarılı yanıt döndür
        return response()->json([
            'success' => true,
            'message' => 'Blog başarıyla güncellendi.',
            'data' => $blog,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Blog kaydını bul
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog bulunamadı.',
            ], 404);
        }

        // Blog kaydını sil
        $blog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blog başarıyla silindi.',
        ]);
    }
}
