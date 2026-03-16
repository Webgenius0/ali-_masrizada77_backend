<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use Illuminate\Http\Request;
use Exception;

class NVPlatformOverviewController extends Controller
{
public function getPlatformOverview(Request $request)
{
    try {
        $type = $request->query('type', 'english');

        // পেজ এবং সেকশন এনুম অনুযায়ী ডাটা ফেচ করা
        $data = CMS::where('page', PageEnum::NVPlatformOverview) // অথবা আপনার নির্দিষ্ট PageEnum
            ->where('section', SectionEnum::INTRO) // আপনার এনুম ফাইলে 'OVERVIEW' যোগ করে নিন
            ->where('type', $type)
            ->where('status', 'active')
            ->first();

        if (!$data) {
            return response()->json(['status' => 'error', 'message' => "Data not found"], 404);
        }

        $meta = $data->metadata;

        return response()->json([
            'status'   => 'success',
            'language' => $type,
            'data'     => [

                // 1. Hero Section
                'hero' => [
                    'title'       => $meta['sec1_title'] ?? null,
                    'description' => $meta['sec1_desc'] ?? null,
                    'media' => [
                        'type'   => 'images',
                        'urls'   => [
                            $data->image1 ? asset($data->image1) : null,
                            $data->image2 ? asset($data->image2) : null,
                            $data->image3 ? asset($data->image3) : null,
                        ],
                    ],
                    'actions' => [
                        ['label' => $meta['sec1_url_title'] ?? null, 'url' => $meta['sec1_url_link'] ?? null, 'primary' => true],
                    ]
                ],

                // 2. Unified Interaction (Video Section)
                'unified_interaction' => [
                    'left_items' => collect($meta['sec2_left'] ?? [])->map(fn($item) => [
                        'title'       => $item['title'] ?? null,
                        'description' => $item['desc'] ?? null
                    ]),
                    'right_content' => [
                        'title'       => $meta['sec2_right_title'] ?? null,
                        'description' => $meta['sec2_right_desc'] ?? null,
                        'video_url'   => $data->video ? asset($data->video) : null,
                    ]
                ],

                // 3. Control & Optimize (Features)
                'control_optimize' => [
                    'title' => $meta['sec3_title'] ?? null,
                    'items' => collect($meta['sec3_items'] ?? [])->map(fn($item) => [
                        'title'       => $item['title'] ?? null,
                        'description' => $item['desc'] ?? null
                    ]),
                ],

                // 4. Connected Across
                'connected_across' => [
                    'title'       => $meta['sec4_title'] ?? null,
                    'description' => $meta['sec4_desc'] ?? null,
                    'image'       => isset($meta['sec4_image']) ? asset($meta['sec4_image']) : null,
                ],

                // 5. Flexible Design
                'flexible_design' => [
                    'title'       => $meta['sec5_title'] ?? null,
                    'description' => $meta['sec5_desc'] ?? null,
                    'image_caption' => $meta['sec5_img_desc'] ?? null,
                    'image'       => isset($meta['sec5_image']) ? asset($meta['sec5_image']) : null,
                ],

                // 6. Human Oversight AI (Gallery)
                'human_oversight' => [
                    'title'    => $meta['sec6_title'] ?? null,
                    'subtitle' => $meta['sec6_subtitle'] ?? null,
                    'items'    => collect($meta['sec6_items'] ?? [])->map(fn($item) => [
                        'title'       => $item['title'] ?? null,
                        'description' => $item['desc'] ?? null,
                        'image'       => isset($item['image']) ? asset($item['image']) : null,
                    ]),
                ],

                // 7. AI Infrastructure (Bottom CTA)
                'ai_infrastructure' => [
                    'title'       => $meta['sec7_title'] ?? null,
                    'description' => $meta['sec7_desc'] ?? null,
                    'image'       => isset($meta['sec7_image']) ? asset($meta['sec7_image']) : null,
                    'action' => [
                        'label' => $meta['sec7_url_title'] ?? null,
                        'url'   => $meta['sec7_url_link'] ?? null
                    ]
                ],
            ]
        ], 200);

    }
    catch (Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}
}
