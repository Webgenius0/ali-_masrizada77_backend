<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use Illuminate\Http\Request;

class DriveThruApiController extends Controller
{
    public function getCMSContent(Request $request)
    {
        try {
            $type = $request->get('type', 'english');

            $data = CMS::where('page', PageEnum::Drive_ThruAIController)
                ->where('section', SectionEnum::INTRO)
                ->where('type', $type)
                ->where('status', 'active')
                ->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'No content found'
                ], 404);
            }

            $meta = $data->metadata;

            // Protita section alada alada kore response ready kora
            $sections = [
                'hero_section' => [
                    'video' => $data->video ? asset($data->video) : null,
                     'title'     => $meta['sec1_title'] ?? '',
                    'desc'      => $meta['sec1_desc'] ?? '',
                    'button'    => [
                        'text' => $meta['sec1_url_title'] ?? '',
                        'link' => $meta['sec1_url_link'] ?? '',
                    ]

                ],
                'section_2' => [
                    'items'       => $meta['sec2_items'] ?? [],
                    'right_title' => $meta['sec2_right_title'] ?? '',
                    'right_desc'  => $meta['sec2_right_desc'] ?? '',
                ],
                'section_3' => [
                    'title' => $meta['sec3_title'] ?? '',
                    'desc'  => $meta['sec3_desc'] ?? '',
                    'image' => $data->image4 ? asset($data->image4) : null,
                ],
                'section_4' => [
                    'title'    => $meta['sec4_title'] ?? '',
                    'subtitle' => $meta['sec4_subtitle'] ?? '',
                    'image'    => isset($meta['sec4_image']) ? asset($meta['sec4_image']) : null,
                    'features' => $this->formatFeatures($meta['sec4_features'] ?? []),
                ]
            ];

            return response()->json([
                'success' => true,
                'data'    => $sections
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function formatFeatures($features)
    {
        if (!is_array($features)) return [];

        return array_map(function ($item) {
            return [
                'icon_image' => isset($item['icon_image']) ? asset($item['icon_image']) : null,
                'title'      => $item['title'] ?? '',
                'content'    => $item['content'] ?? '',
            ];
        }, $features);
    }
}
