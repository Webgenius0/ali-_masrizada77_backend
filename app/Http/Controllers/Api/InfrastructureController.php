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
                    'message' => 'No infrastructure data found'
                ], 404);
            }

            $metadata = $data->metadata;

            // মূল রেসপন্স স্ট্রাকচার
            return response()->json([
                'success' => true,
                'data' => [
                    'header' => [
                        'title'     => $metadata['sec1_title'] ?? '',
                        'sub_title' => $metadata['sec1_sub_title'] ?? '',
                    ],
                    'categories'  => $this->formatCategories($metadata),

                    // এখানে ডেপ্লয়মেন্ট গুলো আলাদা কি (key) তে ভাগ করা হয়েছে
                    'deployment_sections' => $this->formatDeploymentSections($metadata),

                    'comparison_table'  => $this->formatComparison($metadata),
                    'faq_section' => [
                        'title'     => $metadata['faq_title'] ?? '',
                        'sub_title' => $metadata['faq_sub_title'] ?? '',
                        // 'items'     => array_values($metadata['faqs'] ?? []),
                        'faqs'  => array_values(array_map(function ($item) {
                            return [
                                'title' => $item['q'] ?? '',
                                'discription' => $item['a'] ?? ''
                            ];
                        }, $metadata['faqs'] ?? [])),
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
     * Deployment গুলোকে আলাদা আলাদা অবজেক্টে রূপান্তর (deployment_1, 2, 3...)
     */
    private function formatDeploymentSections($metadata)
    {
        $deployments = $metadata['sec3_deployments'] ?? [];
        $formatted = [];

        foreach ($deployments as $index => $item) {
            // ইনডেক্স ১ থেকে শুরু করার জন্য $index + 1
            $keyName = "deployment_" . ($index + 1);

            $features = [];
            if (isset($item['features'])) {
                $features = collect($item['features'])->map(function ($feat) {
                    return [
                        'title' => $feat['title'] ?? '',
                        'sub'   => $feat['sub'] ?? '',
                        'icon'  => isset($feat['icon']) ? asset($feat['icon']) : asset('uploads/no_image.png'),
                    ];
                })->toArray();
            }

            $formatted[$keyName] = [
                'main_title' => $item['title'] ?? '',
                'sub_title'  => $item['sub'] ?? '',
                'description' => $item['desc'] ?? '',
                'features'   => array_values($features)
            ];
        }

        return $formatted;
    }

    private function formatCategories($metadata)
    {
        $categories = [];
        $sec2Data = $metadata['sec2_data'] ?? [];
        $images = $metadata['sec2_images'] ?? [];

        for ($i = 0; $i < 3; $i++) {
            $categories[] = [
                'title' => $sec2Data[$i]['title'] ?? '',
                'desc'  => $sec2Data[$i]['desc'] ?? '',
                'image' => (isset($images[$i]) && $images[$i]) ? asset($images[$i]) : asset('uploads/no_image.png'),
            ];
        }
        return $categories;
    }

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
        })->values();
    }
}
