<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use Illuminate\Http\Request;
use Exception;

class HomePageController extends Controller
{
    public function getHomeIntro(Request $request)
    {
        try {
            $type = $request->query('type', 'english');

            $data = CMS::where('page', PageEnum::HOME)
                ->where('section', SectionEnum::INTRO)
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

                    // 1. Hero Section - Flat structure with action array
                    'hero' => [
                        'title'    => $data->title,
                        'subtitle' => $data->sub_title,
                        'media'    => [
                            'type' => 'video',
                            'url'  => $data->video ? asset($data->video) : null,
                        ],
                        'actions'  => [
                            ['label' => $data->btn_text, 'url' => $data->btn_link, 'primary' => true],
                            ['label' => $meta['btn2_text'] ?? null, 'url' => $meta['btn2_link'] ?? null, 'primary' => false],
                        ]
                    ],

                    // 2. Info Section
                    'info' => [
                        'title'       => $meta['sec2_title'] ?? null,
                        'subtitle'    => $meta['sec2_short'] ?? null,
                        'items'       => collect($meta['sec2_items'] ?? [])->map(fn($item) => [
                            'title'       => $item['title'] ?? null,
                            'description' => $item['desc'] ?? null
                        ]),

                    ],

                    // 3. Features
                    'features' => [
                        'title'    => $meta['feature_title'] ?? null,
                        'subtitle' => $meta['feature_short'] ?? null,
                        'image'    => $data->image1 ? asset($data->image1) : null,
                        'list'     => $meta['feature_list'] ?? [],
                        'footer_link' => [
                            'label' => $meta['sec2_link_title'] ?? null,
                            'url'   => $meta['sec2_link_url'] ?? null
                        ]
                    ],

                    // 4. CX Solutions
                    'cx_solutions' => [
                        'title'       => $meta['cx_title'] ?? null,
                        'description' => $meta['cx_description'] ?? null,
                        'items'       => collect($meta['cx_features'] ?? [])->map(fn($item) => [
                            'title'       => $item['title'] ?? null,
                            'description' => $item['desc'] ?? null,
                            'image'       => isset($item['img_path']) ? asset($item['img_path']) : null,
                        ]),
                        'link' => ['label' => $meta['cx_link_title'] ?? null, 'url' => $meta['cx_link_add'] ?? null]
                    ],

                    // 5. Case Study & Industry
                    'case_study' => [
                        'title'       => $meta['case_sec_title'] ?? null,
                        'subtitle'    => $meta['case_sec_subtitle'] ?? null,
                        'description' => $meta['case_description'] ?? null,
                        'image'       => $data->image2 ? asset($data->image2) : null,
                        'statistics' => [
                            [
                                'value' => $meta['stat_1_val'] ?? '0',
                                'label' => $meta['stat_1_text'] ?? ''
                            ],
                            [
                                'value' => $meta['stat_2_val'] ?? '0',
                                'label' => $meta['stat_2_text'] ?? ''
                            ],
                            [
                                'value' => $meta['stat_3_val'] ?? '0',
                                'label' => $meta['stat_3_text'] ?? ''
                            ]
                        ],
                        'industry_items' => collect($meta['industry_items'] ?? [])->map(fn($item) => [
                            'title' => $item['title'] ?? null,
                            'image' => isset($item['img']) ? asset($item['img']) : null,
                        ])
                    ],

                    // 6. AI Deployment Agents
                    'ai_agents' => [
                        'title'       => $meta['ai_agents_title'] ?? null,
                        'description' => $meta['ai_agents_discription'] ?? null,
                        'steps'       => collect($meta['ai_deployment'] ?? [])->map(fn($step, $index) => [
                            'step_no'     => $index + 1,
                            'title'       => $step['title'] ?? null,
                            'description' => $step['desc'] ?? null,
                        ])
                    ],

                    // 7. Bottom CTA
                    'cta' => [
                        'description' => $meta['bottom_desc'] ?? null,
                        'media_url'   => $data->bottom_video ? asset($data->bottom_video) : null,
                        'action'      => [
                            'label' => $meta['bottom_btn_title'] ?? null,
                            'url'   => $meta['bottom_btn_link'] ?? null
                        ]
                    ],

                    // 8. FAQs
                    'faqs' => [
                        'title'       => $meta['faq_title'] ?? 'Frequently Asked Questions',
                        'description' => $meta['faq_discription'] ?? null,
                        'items'       => collect($meta['faq'] ?? [])->map(fn($faq) => [
                            'question' => $faq['q'] ?? null,
                            'answer'   => $faq['a'] ?? null,
                        ])
                    ],


                    'last_better_cx' => [
                        'title'       => $meta['last_bettercx_title'] ?? 'Better CX, better Business',
                        'description' => $meta['last_bettercx_desc'] ?? null,

                    ],
                    'logo_img1' => isset($meta['logo_img1']) ? asset($meta['logo_img1']) : null,
                    'logo_img2' => isset($meta['logo_img2']) ? asset($meta['logo_img2']) : null,
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
