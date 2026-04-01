<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CMS;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;

class PartnerController extends Controller
{
    public function getPartnerData(Request $request)
    {
        // ল্যাঙ্গুয়েজ টাইপ ধরবে (ডিফল্ট english)
        $type = $request->query('type', 'english');

        // ডাটাবেস থেকে স্লাগ অনুযায়ী ডেটা আনা (আপনার টেবিল স্ট্রাকচার অনুযায়ী 'partner' পেজ)
        $cms = CMS::where('page', PageEnum::Partner) // অথবা আপনার নির্দিষ্ট PageEnum
            ->where('section', SectionEnum::INTRO) // আপনার এনুম ফাইলে 'OVERVIEW' যোগ করে নিন
            ->where('type', $type)
            ->where('status', 'active')
            ->first();

        if (!$cms) {
            return response()->json([
                'success' => false,
                'message' => 'No content found for this type.'
            ], 404);
        }

        $meta = $cms->metadata;


        $response = [
            'success' => true,
            'page_title' => "Become a Partner",
            'data' => [

                'hero' => [
                    'title'    => $meta['sec1_title'] ?? '',
                    'subtitle' => $meta['sec1_sub_title'] ?? '',
                    'image'    => isset($meta['sec1_image']) ? asset($meta['sec1_image']) : null,
                ],

                'ecosystem' => [
                    'title'    => $meta['eco_title'] ?? '',
                    'subtitle' => $meta['eco_sub_title'] ?? '',
                    'items'    => collect($meta['ecosystem'] ?? [])->map(function ($item) {
                        return [
                            'logo' => isset($item['image']) ? asset($item['image']) : null,
                            'url'  => $item['link'] ?? '#',
                        ];
                    }),
                ],


                'features' => collect($meta['features'] ?? [])->map(function ($item) {
                    return [
                        'title'       => $item['title'] ?? '',
                        'description' => $item['desc'] ?? '',
                        'icon'        => isset($item['icon']) ? asset($item['icon']) : null,
                    ];
                }),

                'who_we_partner' => [
                    'title'       => $meta['who_title'] ?? '',
                    'description' => $meta['who_desc'] ?? '',
                    'image'       => isset($meta['who_image']) ? asset($meta['who_image']) : null,
                ],


                'main_faq' => [
                    'title' => $meta['faq_title'] ?? '',
                    'image' => isset($meta['faq_image']) ? asset($meta['faq_image']) : null,
                    'faqs'     => array_values(array_map(function ($item) {
                        return [
                            'title' => $item['q'] ?? '',
                            'discription' => $item['a'] ?? ''
                        ];
                    }, $meta['faqs'] ?? [])),
                ],


                'extra_faq' => [
                    'title'    => $meta['extra_faq_title'] ?? '',
                    'subtitle' => $meta['extra_faq_sub'] ?? '',

                    'faqs'     => array_values(array_map(function ($item) {
                        return [
                            'title' => $item['q'] ?? '',
                            'discription' => $item['a'] ?? ''
                        ];
                    }, $meta['extra_faqs'] ?? [])),
                ],
            ]
        ];

        return response()->json($response, 200);
    }
}
