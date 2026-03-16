<?php

namespace App\Http\Controllers\Api;
use App\Models\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

class SlugCategoryApiController extends Controller
{
    public function getCmsDataBySlug(Request $request, $slug)
{
    try {
        // ১. রিকোয়েস্ট থেকে টাইপ নেওয়া (ডিফল্ট english)
        $type = $request->query('type', 'english');

        // ২. স্লাগ এবং টাইপ অনুযায়ী ডেটা খোঁজা
        $data = CMS::where('slug', $slug)
            ->where('type', $type)
            ->where('status', 'active')
            ->first();

        // ৩. ডেটা না পাওয়া গেলে ৪MD৪ রেসপন্স
        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => "Content not found for slug: {$slug} with type: {$type}"
            ], 404);
        }

        $meta = $data->metadata;

        // ৪. আপনার চাহিদামতো ফরম্যাট করা রেসপন্স
        return response()->json([
            'status'   => 'success',
            'language' => $type,
            'slug'     => $slug,

                // 3. Features Section
                'features' => [
                    'title'    => $meta['feature_title'] ?? null,
                    'subtitle' => $meta['feature_short'] ?? null,
                    'image'    => $data->image1 ? asset($data->image1) : null,
                    'list'     => $meta['feature_list'] ?? [],
                    'footer_link' => [
                        'label' => $meta['sec2_link_title'] ?? null,
                        'url'   => $meta['sec2_link_url'] ?? null
                    ]
                ], 200]);

    } catch (Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}
}
