<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Exception;

class FinancialServicesController extends Controller
{
    public function getFinancialContent(Request $request)
    {
        try {
            // ল্যাঙ্গুয়েজ টাইপ ধরুন (ডিফল্ট ইংরেজি)
            $type = $request->query('type', 'english');

            $cms = CMS::where('page', 'financialservices')
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
            $data = [
                'hero_section' => [
                    'title' => $metadata['sec1_title'] ?? '',
                    'sub_title' => $metadata['sec1_sub_title'] ?? '',
                    'video_url_1' => isset($metadata['sec1_video_1']) ? asset($metadata['sec1_video_1']) : null,
                    'video_url_2' => isset($metadata['sec1_video_2']) ? asset($metadata['sec1_video_2']) : null,
                    'video_url_3' => isset($metadata['sec1_video_3']) ? asset($metadata['sec1_video_3']) : null,
                ],
                'case_study_section' => [
                    'title'       => $metadata['sec2_title'] ?? '',
                    'sub_title'   => $metadata['sec2_sub_title'] ?? '',
                    'description' => $metadata['sec2_desc'] ?? '',
                    'video_url'   => isset($metadata['sec2_video']) ? asset($metadata['sec2_video']) : null,
                    'image_url'   => isset($metadata['sec2_image']) ? asset($metadata['sec2_image']) : null,
                    'statistics'  => collect($metadata['sec2_stats'] ?? [])->map(function ($stat) {
                        return [
                            'percentage' => $stat['val'] ?? '',
                            'label'      => $stat['title'] ?? '',
                        ];
                    }),
                ],
                'smarter_patient' => [
                    'title' => $metadata['sec3_title'] ?? '',
                    'description' => $metadata['sec3_desc'] ?? '',
                    'side_image' => isset($metadata['sec3_image']) ? asset($metadata['sec3_image']) : null,
                    'features' => collect($metadata['sec3_items'] ?? [])->map(function ($item) {
                        return [
                            'icon_url' => isset($item['icon']) ? asset($item['icon']) : null,
                            'title' => $item['title'] ?? '',
                            'description' => $item['desc'] ?? '',
                        ];
                    }),
                ],
                'operational_ai_patient' => [
                    'title' => $metadata['sec4_title'] ?? '',
                    'sub_title' => $metadata['sec4_sub_title'] ?? '',
                    'features' => collect($metadata['sec4_items'] ?? [])->map(function ($item) {
                        return [
                            'icon_url' => isset($item['icon']) ? asset($item['icon']) : null,
                            'title' => $item['title'] ?? '',
                        ];
                    }),
                ],
                'designed_for_provide' => [
                    'title' => $metadata['sec5_title'] ?? '',
                    'description' => $metadata['sec5_desc'] ?? '',
                    'video_url' => isset($metadata['sec5_video']) ? asset($metadata['sec5_video']) : null,
                ],
                'faq_section' => [
                    'title' => $metadata['sec6_title'] ?? '',
                    'sub_title' => $metadata['sec6_sub_title'] ?? '',
                    'faqs' => collect($metadata['sec6_faqs'] ?? [])->map(function ($faq) {
                        return [
                            'question' => $faq['q'] ?? '',
                            'answer' => $faq['a'] ?? '',
                        ];
                    }),
                ],
            ];

            return response()->json([
                'success' => true,
                'status_code' => 200,
                'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
