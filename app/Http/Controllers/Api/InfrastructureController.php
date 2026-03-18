<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use Illuminate\Http\Request;
use Exception;

class InfrastructureController extends Controller
{
    /**
     * Get Infrastructure Data for Frontend
     */
public function getInfrastructureData(Request $request)
    {
        try {
            $type = $request->query('type', 'english');
            $page = PageEnum::Infrastructure ?? 'infrastructure';
            $section = SectionEnum::INTRO ?? 'MAIN';

            $data = CMS::where('page', $page)
                       ->where('section', $section)
                       ->where('type', $type)
                       ->where('status', 'active')
                       ->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'No infrastructure data found for ' . $type
                ], 404);
            }

            $metadata = $data->metadata;

            // ফ্রন্টএন্ডে পাঠানোর জন্য ডাটা ফরম্যাট করা
            return response()->json([
                'success' => true,
                'data' => [
                    'header' => [
                        'title'     => $metadata['sec1_title'] ?? '',
                        'sub_title' => $metadata['sec1_sub_title'] ?? '',
                    ],
                    'categories'  => $this->formatCategories($metadata),
                    'deployments' => $this->formatDeployments($metadata),
                    'comparison'  => $this->formatComparison($metadata),
                    'faq' => [
                        'title'     => $metadata['faq_title'] ?? '',
                        'sub_title' => $metadata['faq_sub_title'] ?? '',
                        'items'     => $metadata['faqs'] ?? []
                    ]
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ক্যাটাগরি এবং ইমেজ URL ফরম্যাট করা
     */
    private function formatCategories($metadata)
    {
        $categories = [];
        $titles = $metadata['sec2_data'] ?? [];
        $images = $metadata['sec2_images'] ?? [];

        for ($i = 0; $i < 3; $i++) {
            $categories[] = [
                'title' => $titles[$i]['title'] ?? '',
                'desc'  => $titles[$i]['desc'] ?? '',
                'image' => isset($images[$i]) && $images[$i] ? asset($images[$i]) : null,
            ];
        }
        return $categories;
    }

    /**
     * Deployment সেকশনের আইকনগুলোর পূর্ণাঙ্গ URL করা
     */
    private function formatDeployments($metadata)
    {
        $deployments = $metadata['sec3_deployments'] ?? [];

        return collect($deployments)->map(function ($item) {
            if (isset($item['features'])) {
                $item['features'] = collect($item['features'])->map(function ($feat) {
                    return [
                        'title' => $feat['title'] ?? '',
                        'sub'   => $feat['sub'] ?? '',
                        'icon'  => isset($feat['icon']) ? asset($feat['icon']) : asset('uploads/no_image.png'),
                    ];
                })->toArray();
            }
            return $item;
        });
    }

    /**
     * Comparison Table (c, f, h, o) কে পূর্ণাঙ্গ নামে রূপান্তর
     */
    private function formatComparison($metadata)
    {
        $rows = $metadata['table_rows'] ?? [];

        return collect($rows)->map(function ($row) {
            return [
                'feature' => $row['f'] ?? '',
                'cloud'   => $row['c'] ?? '',
                'hybrid'  => $row['h'] ?? '',
                'on_prem' => $row['o'] ?? '',
            ];
        });
    }
}
