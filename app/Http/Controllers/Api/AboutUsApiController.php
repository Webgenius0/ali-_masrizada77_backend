<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use Illuminate\Http\Request;

class AboutUsApiController extends Controller
{
    public function getCMSContent(Request $request)
    {
        try {
            // Default language english, kintu query theke neya jabe
            $type = $request->get('type', 'english');

            $data = CMS::where('page', PageEnum::Aboutus)
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

            // Section wise response structure
          $sections = [
    'hero_section' => [
        'title'    => $meta['hero_title'] ?? '',
        'subtitle' => $meta['hero_subtitle'] ?? '',
        'image'    => $data->image1 ? asset($data->image1) : null,
    ],
    'intro_section' => [
        'title'    => $meta['sec2_title'] ?? '',
        'subtitle' => $meta['sec2_subtitle'] ?? '',
    ],
    'image_qa_section' => [
        'title'       => $meta['sec3_title'] ?? '',
        'image'       => isset($meta['sec3_image']) ? asset($meta['sec3_image']) : null,
        'description' => $meta['sec3_img_desc'] ?? '',
        // এখানে array_values ব্যবহার করে অবজেক্টকে অ্যারেতে রূপান্তর করা হয়েছে
        'faqs'     => array_values(array_map(function($item) {
            return [
                'title' => $item['q'] ?? '',
                'discription' => $item['a'] ?? ''
            ];
        }, $meta['sec3_qa'] ?? [])),
    ],
    'highlights_section' => [
        'title'    => $meta['sec4_title'] ?? '',
        'subtitle' => $meta['sec4_subtitle'] ?? '',
    ],
    'extra_qa_section' => [
        'title'    => $meta['sec5_title'] ?? '',
        'subtitle' => $meta['sec5_subtitle'] ?? '',
        'faqs'  => array_values(array_map(function($item) {
            return [
                'title' => $item['q'] ?? '',
                'description' => $item['a'] ?? ''
            ];
        }, $meta['sec5_qa'] ?? [])),
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
}
