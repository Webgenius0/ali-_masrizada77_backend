<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Models\POST;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use Illuminate\Http\Request;

class CareerController extends Controller
{
public function getCareerData(Request $request)
{
    try {
        // ল্যাঙ্গুয়েজ টাইপ হ্যান্ডেল করা (Default: english)
        $type = $request->query('type', 'english');

        // পোস্টের জন্য ছোট কোড (en/de) ম্যাপ করা
        $postType = ($type == 'german' || $type == 'de') ? 'de' : 'en';

        // ১. CMS থেকে সেকশন ডেটা নিয়ে আসা
        $careerData = CMS::where('page', PageEnum::CarrerPage)
            ->where('section', SectionEnum::INTRO)
            ->where('type', $type)
            ->first();

        if (!$careerData) {
            return response()->json([
                'success' => false,
                'message' => 'Career content not found',
            ], 404);
        }

        $m = $careerData->metadata;

        // ২. অ্যাক্টিভ জব পোস্টগুলো নিয়ে আসা
        $posts = Post::where('status', 'active')
            ->latest()
            ->get()
            ->map(function ($post) use ($postType) {
                return [
                    'id'             => $post->id,
                    'title'          => $postType == 'de' ? ($post->title_de ?? $post->title) : $post->title,
                    'team'           => $post->team,
                    'location'       => $post->location,
                    'formatted_date' => $post->created_at->format('d M, Y'),
                ];
            });

        // ৩. সব ডেটা একসাথে ফরম্যাট করা
        $formattedData = [
            'hero_section' => [
                'title'       => $m['hero_title'] ?? '',
                'description' => $m['hero_desc'] ?? '',
                'label'       => $m['hero_btn_text'] ?? '',
                'image'       => isset($m['hero_image']) ? asset($m['hero_image']) : null,
            ],
            'job_section' => [
                'title' => $m['job_heading'] ?? 'Open Positions',
                'posts' => $posts // এখানে সব জব পোস্ট ইনজেক্ট করা হয়েছে
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
            'message' => 'Career page data retrieved successfully',
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
