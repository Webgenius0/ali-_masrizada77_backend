<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class HealthcareApiController extends Controller
{
    public function getHealthcareContent(Request $request)
    {
        try {
            // ল্যাঙ্গুয়েজ টাইপ ধরুন (ডিফল্ট ইংরেজি)
            $type = $request->query('type', 'english');

            $cms = CMS::where('page', 'healthcare')
                      ->where('section', 'main_content')
                      ->where('type', $type)
                      ->first();

            if (!$cms) {
                return response()->json([
                    'success' => false,
                    'message' => 'No content found for ' . $type
                ], 404);
            }

            $metadata = $cms->metadata;

            // ডাটা ট্রান্সফর্ম করা হচ্ছে যাতে ফ্রন্টএন্ড ফুল ইউআরএল পায়
            $data = [
                'hero_section' => [
                    'title' => $metadata['sec1_title'] ?? '',
                    'sub_title' => $metadata['sec1_sub_title'] ?? '',
                    'video_url' => isset($metadata['sec1_video']) ? asset($metadata['sec1_video']) : null,
                ],
                'spotlight_section' => [
                    'title' => $metadata['sec2_title'] ?? '',
                    'sub_title' => $metadata['sec2_sub_title'] ?? '',
                    'video_url' => isset($metadata['sec2_video']) ? asset($metadata['sec2_video']) : null,
                    'statistics' => collect($metadata['sec2_stats'] ?? [])->map(function($stat) {
                        return [
                            'percentage' => $stat['val'] ?? '',
                            'label' => $stat['title'] ?? '',
                        ];
                    }),
                ],
                'communication_section' => [
                    'title' => $metadata['sec3_title'] ?? '',
                    'description' => $metadata['sec3_desc'] ?? '',
                    'side_image' => isset($metadata['sec3_image']) ? asset($metadata['sec3_image']) : null,
                    'features' => collect($metadata['sec3_items'] ?? [])->map(function($item) {
                        return [
                            'icon_url' => isset($item['icon']) ? asset($item['icon']) : null,
                            'title' => $item['title'] ?? '',
                            'description' => $item['desc'] ?? '',
                        ];
                    }),
                ],
                'operations_section' => [
                    'title' => $metadata['sec4_title'] ?? '',
                    'sub_title' => $metadata['sec4_sub_title'] ?? '',
                    'features' => collect($metadata['sec4_items'] ?? [])->map(function($item) {
                        return [
                            'icon_url' => isset($item['icon']) ? asset($item['icon']) : null,
                            'title' => $item['title'] ?? '',
                        ];
                    }),
                ],
                'regulated_healthcare' => [
                    'title' => $metadata['sec5_title'] ?? '',
                    'description' => $metadata['sec5_desc'] ?? '',
                    'video_url' => isset($metadata['sec5_video']) ? asset($metadata['sec5_video']) : null,
                ],
                'faq_section' => [
                    'title' => $metadata['sec6_title'] ?? '',
                    'sub_title' => $metadata['sec6_sub_title'] ?? '',

                    'faqs' => collect($metadata['sec6_faqs'] ?? [])->map(function($faq) {
                        return [
                            'title' => $faq['q'] ?? '',
                            'description' => $faq['a'] ?? '',
                        ];
                    }),
                ],
            ];

            return response()->json([
                'success' => true,
                'status_code' => 200,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
