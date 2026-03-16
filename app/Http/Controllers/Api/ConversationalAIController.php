<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use Illuminate\Http\Request;
use Exception;

class ConversationalAIController extends Controller
{
    public function getConversationalContent(Request $request)
    {
        $type = $request->query('type', 'english');

        // আপনার Web Controller এর সাথে মিল রেখে ফিল্টার করা হলো
        $data = CMS::where('page', PageEnum::ConversationalAI) // অথবা PageEnum::ConversationalAI->value
            ->where('section', SectionEnum::INTRO)              // অথবা SectionEnum::INTRO->value
            ->where('type', $type)
            ->where('status', 'active')
            ->first();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'No content found for this type.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'hero_section' => [
                    'title'       => $data->metadata['sec1_title'] ?? '',
                    'description' => $data->metadata['sec1_desc'] ?? '',
                    'button_text' => $data->metadata['sec1_url_title'] ?? '',
                    'button_url'  => $data->metadata['sec1_url_link'] ?? '',
                    'video'       => $data->video ? asset($data->video) : null,
                ],
                'core_capabilities' => [
                    'main_title'       => $data->metadata['sec2_right_title'] ?? '',
                    'main_description' => $data->metadata['sec2_right_desc'] ?? '',
                    'items'            => $data->metadata['sec2_items'] ?? [],
                ],
                'built_for_operational' => [
                    'title'       => $data->metadata['sec3_title'] ?? '',
                    'description' => $data->metadata['sec3_desc'] ?? '',
                    'video'       => $data->image4 ? asset($data->image4) : null,
                ],
                'more_than_just_a_phone' => [
                    'title'    => $data->metadata['sec4_title'] ?? '',
                    'subtitle' => $data->metadata['sec4_subtitle'] ?? '',
                    'features' => $data->metadata['sec4_features'] ?? [],
                    'image'    => isset($data->metadata['sec4_image']) ? asset($data->metadata['sec4_image']) : null,
                ],
                'instant_support' => [
                    'main_title'       => $data->metadata['sec5_main_title'] ?? '',
                    'main_description' => $data->metadata['sec5_main_desc'] ?? '',
                    'items' => collect($data->metadata['sec5_items'] ?? [])->map(function ($item) {
                        return [
                            'title'       => $item['title'] ?? '',
                            'sub_title'   => $item['sub'] ?? '',
                            'description' => $item['desc'] ?? '',
                            'image'       => !empty($item['image']) ? asset($item['image']) : null,
                        ];
                    }),
                ]
            ]
        ], 200);
    }
    public function instant_support(Request $request)
    {
        $type = $request->query('type', 'english');

        // আপনার Web Controller এর সাথে মিল রেখে ফিল্টার করা হলো
        $data = CMS::where('page', PageEnum::ConversationalAI) // অথবা PageEnum::ConversationalAI->value
            ->where('section', SectionEnum::INTRO)              // অথবা SectionEnum::INTRO->value
            ->where('type', $type)
            ->where('status', 'active')
            ->first();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'No content found for this type.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'instant_support' => [
                'main_title'       => $data->metadata['sec5_main_title'] ?? '',
                'main_description' => $data->metadata['sec5_main_desc'] ?? '',
                'items' => collect($data->metadata['sec5_items'] ?? [])->map(function ($item) {
                    return [
                        'title'       => $item['title'] ?? '',
                        'sub_title'   => $item['sub'] ?? '',
                        'description' => $item['desc'] ?? '',
                        'image'       => !empty($item['image']) ? asset($item['image']) : null,
                    ];
                }),
            ]
        ], 200);
    }
}
