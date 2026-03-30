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
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Data not found for type: ' . $type
                ], 404);
            }

            $meta = $data->metadata ?? [];

            return response()->json([
                'status'   => 'success',
                'language' => $type,
                'data'     => [

                    // ─── 1. Hero Section ────────────────────────────────────────
                    'hero' => [
                        'title'    => $data->title,
                        'subtitle' => $data->sub_title,
                        'media'    => [
                            'type' => 'video',
                            'url'  => $data->video ? asset($data->video) : null,
                        ],
                        // 'actions' => [
                        //     [
                        //         'label'   => $data->btn_text,
                        //         'url'     => $data->btn_link,
                        //         'primary' => true,
                        //     ],
                        //     [
                        //         'label'   => $meta['btn2_text'] ?? null,
                        //         'url'     => $meta['btn2_link'] ?? null,
                        //         'primary' => false,
                        //     ],
                        // ],
                    ],

                    // ─── 2. Info Section ─────────────────────────────────────────
                    'info' => [
                        'title'    => $meta['sec2_title'] ?? null,
                        'subtitle' => $meta['sec2_short'] ?? null,
                        'items'    => collect($meta['sec2_items'] ?? [])
                            ->map(fn($item) => [
                                'title'       => $item['title'] ?? null,
                                'description' => $item['desc']  ?? null,
                            ])->values(),
                    ],

                    // ─── 3. Case Study & Industry Section ────────────────────────
                    // (blade-এ section 3, controller-এ আগে section 5 ছিল — fix করা হয়েছে)
                    'case_study' => [
                        'title'    => $meta['case_sec_title']    ?? null,
                        'subtitle' => $meta['case_sec_subtitle']  ?? null,

                        // LEFT SIDE
                        'spotlight' => [
                            'image_subtitle' => $meta['case_image_subtile'] ?? null, // ✅ missing ছিল
                            'image'          => $data->image2 ? asset($data->image2) : null,
                            'description'    => $meta['case_description'] ?? null,
                            'statistics'     => [
                                [
                                    'value' => $meta['stat_1_val']  ?? '0',
                                    'label' => $meta['stat_1_text'] ?? null,
                                ],
                                [
                                    'value' => $meta['stat_2_val']  ?? '0',
                                    'label' => $meta['stat_2_text'] ?? null,
                                ],
                                [
                                    'value' => $meta['stat_3_val']  ?? '0',
                                    'label' => $meta['stat_3_text'] ?? null,
                                ],
                            ],
                        ],

                        // RIGHT SIDE
                        'industry' => [
                            'description' => $meta['industry_description'] ?? null, // ✅ missing ছিল
                            'items'       => collect($meta['industry_items'] ?? [])
                                ->map(fn($item) => [
                                    'title' => $item['title'] ?? null,
                                    'image' => isset($item['img']) ? asset($item['img']) : null,
                                ])->values(),
                        ],
                    ],

                    // ─── 4. AI Powered CX Section ────────────────────────────────
                    'cx_solutions' => [
                        'title'       => $meta['cx_title']       ?? null,
                        'description' => $meta['cx_description'] ?? null,
                        // 'link'        => [
                        //     'label' => $meta['cx_link_title'] ?? null,
                        //     'url'   => $meta['cx_link_add']   ?? null,
                        // ],
                        // ─── 5. CX Features (blade-এ section 5, cx-এর ভেতরে) ────
                        'features' => collect($meta['cx_features'] ?? [])
                            ->map(fn($item) => [
                                'title'       => $item['title']    ?? null,
                                'description' => $item['desc']     ?? null,
                                'image'       => isset($item['img_path']) ? asset($item['img_path']) : null,
                            ])->values(),
                    ],

                    // ─── 5. Bottom Video & CTA ───────────────────────────────────
                    'cta' => [
                        'title' => $meta['bottom_btn_title'] ?? null,
                        'description' => $meta['bottom_desc'] ?? null,

                        'media'       => [
                            'type' => 'video',
                            // blade-এ name="bottom_video" কিন্তু DB-তে image4 — দুটোই check
                            'url'  => ($data->image4 ?? null)
                                ? asset($data->image4)
                                : (($data->bottom_video ?? null) ? asset($data->bottom_video) : null),
                        ]
                    ],

                    // ─── 6. AI Deployment Agents ─────────────────────────────────
                    'ai_agents' => [
                        'title'       => $meta['ai_agents_title']        ?? null,
                        'description' => $meta['ai_agents_discription']  ?? null,
                        'steps'       => collect($meta['ai_deployment'] ?? [])
                            ->map(fn($step, $index) => [
                                'step_no'     => $index + 1,
                                'title'       => $step['title'] ?? null,
                                'description' => $step['desc']  ?? null,
                            ])->values(),
                    ],

                    // ─── 7. FAQ Section ──────────────────────────────────────────
                    'faqs' => [
                        'title'       => $meta['faq_title']        ?? 'Frequently Asked Questions',
                        'description' => $meta['faq_discription']  ?? null,
                        'items'       => collect($meta['faq'] ?? [])
                            ->map(fn($faq) => [
                                'question' => $faq['q'] ?? null,
                                'answer'   => $faq['a'] ?? null,
                            ])->values(),
                    ],

                    // ─── 8. Better CX, Better Business ───────────────────────────
                    'last_better_cx' => [
                        'title'       => $meta['last_bettercx_title'] ?? 'Better CX, better Business',
                        'description' => $meta['last_bettercx_desc']  ?? null,
                    ],

                    // ─── 9. Logo Images ───────────────────────────────────────────
                    'logos' => [
                       'logo_img1'=> isset($meta['logo_img1']) ? asset($meta['logo_img1']) : null,
                     'logo_img2'=>  isset($meta['logo_img2']) ? asset($meta['logo_img2']) : null,
                    ],

                ],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
