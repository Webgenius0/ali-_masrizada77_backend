<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use Illuminate\Http\Request;

class TrustApiController extends Controller
{
public function getFullTrustContent(Request $request)
{
    try {
        $type = $request->get('type', 'english');

        // 🔹 Heading (based on your backend data)
        $heading = CMS::where('slug', 'trust_heading')
            ->where('page', 'trust_heading')
            ->where('section', 'sidebar')
            ->where('type', $type)
            ->first();

        // 🔹 Trust Content (existing API logic)
        $trust = CMS::where('page', PageEnum::Trust)
            ->where('section', SectionEnum::TRUST)
            ->where('type', $type)
            ->where('status', 'active')
            ->first();

        if (!$heading && !$trust) {
            return response()->json([
                'success' => false,
                'message' => 'No content found'
            ], 404);
        }

        // ✅ Heading Data (metadata থেকে আসবে)
        $headingData = $heading ? [
            'title'       => $heading->metadata['contact_title'] ?? '',
            'description' => $heading->metadata['contact_desc'] ?? '',
            'email'       => $heading->metadata['contact_email'] ?? '',
            'phone'       => $heading->metadata['contact_phone'] ?? '',
        ] : null;

        // ✅ Trust Data
        $trustData = null;

        if ($trust) {
            $meta = $trust->metadata;

            $trustData = [
                'hero_section' => [
                    'title'       => $meta['hero_title'] ?? '',
                    'description' => $meta['hero_desc'] ?? '',
                    'image'       => $trust->image1 ? asset($trust->image1) : null,
                    'button'      => [
                        'text' => $meta['hero_btn_text'] ?? '',
                        'url'  => $meta['hero_btn_url'] ?? '',
                    ]
                ],
                'alignment_standards' => [
                    'title'       => $meta['standards_title'] ?? '',
                    'description' => $meta['standards_desc'] ?? '',
                    'items'       => array_values(array_map(function ($item) {
                        return [
                            'icon'        => isset($item['icon']) ? asset($item['icon']) : null,
                            'title'       => $item['title'] ?? '',
                            'description' => $item['desc'] ?? '',
                            'badge'       => $item['badge'] ?? '',
                            'footer_text' => $item['footer_text'] ?? '',
                            'pdf'         => isset($item['pdf']) ? asset($item['pdf']) : null,
                        ];
                    }, $meta['standards_items'] ?? [])),
                ],
                'data_protection' => [
                    'title'       => $meta['protection_title'] ?? '',
                    'description' => $meta['protection_desc'] ?? '',
                    'items'       => array_values(array_map(function ($item) {
                        return [
                            'icon'        => isset($item['icon']) ? asset($item['icon']) : null,
                            'title'       => $item['title'] ?? '',
                            'description' => $item['desc'] ?? '',
                        ];
                    }, $meta['protection_items'] ?? [])),
                ]
            ];
        }

        return response()->json([
            'success'  => true,
            'language' => $type,
            'data'     => [
                'heading' => $headingData,
                'trust'   => $trustData
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}
