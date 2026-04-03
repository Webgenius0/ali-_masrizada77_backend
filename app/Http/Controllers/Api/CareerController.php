<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function getCareerData(Request $request)
    {
        try {
            $type = $request->query('type', 'english');

            $careerData = CMS::where('page', PageEnum::CarrerPage)
                ->where('section', SectionEnum::INTRO)
                ->where('type', $type)
                ->first();

            if (!$careerData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Career data not found',
                ], 404);
            }

            $m = $careerData->metadata;

            // --- সেকশন অনুযায়ী ডেটা আলাদা করা ---
            $formattedData = [
                'hero_section' => [
                    'title'       => $m['hero_title'] ?? '',
                    'description' => $m['hero_desc'] ?? '',
                    'label'       => $m['hero_btn_text'] ?? '', // বানান সংশোধন: lable -> label
                    'image'       => isset($m['hero_image']) ? asset($m['hero_image']) : null,
                ],
                'job_section' => [
                    'title' => $m['job_heading'] ?? '', // নতুন যোগ করা জব হেডিং
                ],
                'stats_section' => [
                    'title'         => $m['stats_title'] ?? '',
                    'description'   => $m['stats_desc'] ?? '',
                    'main_image'    => isset($m['stats_image']) ? asset($m['stats_image']) : null,
                    'counter_title' => $m['stat_emp_title'] ?? '',
                    'stats' => [
                        [
                            'label'       => $m['stat_emp_label'] ?? 'Total Employees',
                            'count'       => isset($m['stat_emp']) ? (int) filter_var($m['stat_emp'], FILTER_SANITIZE_NUMBER_INT) : 0,
                            'description' => $m['stat_emp_desc'] ?? '',
                        ],
                        [
                            'label'       => $m['stat_hours_label'] ?? 'Working Hours',
                            'count'       => isset($m['stat_hours']) ? (int) filter_var($m['stat_hours'], FILTER_SANITIZE_NUMBER_INT) : 0,
                            'description' => $m['stat_hours_desc'] ?? '',
                        ],
                        [
                            'label'       => $m['stat_offices_label'] ?? 'Global Offices',
                            'count'       => isset($m['stat_offices']) ? (int) filter_var($m['stat_offices'], FILTER_SANITIZE_NUMBER_INT) : 0,
                            'description' => $m['stat_offices_desc'] ?? '',
                        ]
                    ]
                ],
                'benefits_section' => [
                    'title'  => $m['benefits_title'] ?? '',
                    'footer' => $m['benefits_footer'] ?? '',
                    'list'   => $m['benefits_list'] ?? []
                ]
            ];

            return response()->json([
                'success' => true,
                'message' => 'Career data retrieved successfully',
                'data'    => $formattedData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
