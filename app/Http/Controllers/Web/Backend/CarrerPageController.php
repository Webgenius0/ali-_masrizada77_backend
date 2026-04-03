<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use App\Helpers\Helper;
use App\Models\CMS;
use Exception;
use App\Services\CmsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CarrerPageController extends Controller
{
    protected $cmsService;
    public $page;
    public $section;
    public $uploadPath;

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
        $this->page = PageEnum::CarrerPage;
        $this->section = SectionEnum::INTRO;
        $this->uploadPath = 'cms/career';
    }

    public function index(Request $request)
    {
        $type = $request->query('type', 'english');
        $data = CMS::where('page', $this->page)
                   ->where('section', $this->section)
                   ->where('type', $type)
                   ->first();

        return view('backend.layouts.cms.carrer.index', compact('data', 'type'));
    }

public function content(Request $request)
    {
        try {
            $type = $request->get('type', 'english');
            $section = CMS::where('page', $this->page)
                ->where('section', $this->section)
                ->where('type', $type)
                ->first();

            $oldMetadata = $section->metadata ?? [];

            // --- ১. ইমেজ হ্যান্ডলিং (Hero, Stats) ---
            $hero_image = $oldMetadata['hero_image'] ?? null;
            if ($request->hasFile('hero_image')) {
                if ($hero_image && file_exists(public_path($hero_image))) @unlink(public_path($hero_image));
                $hero_image = Helper::fileUpload($request->file('hero_image'), $this->uploadPath, 'hero_' . time());
            }

            $stats_image = $oldMetadata['stats_image'] ?? null;
            if ($request->hasFile('stats_image')) {
                if ($stats_image && file_exists(public_path($stats_image))) @unlink(public_path($stats_image));
                $stats_image = Helper::fileUpload($request->file('stats_image'), $this->uploadPath, 'stats_' . time());
            }

            // --- ২. মেটাডাটা প্রিপারেশন ---
            $metadata = [
                // Section 1: Hero
                'hero_title'        => $request->hero_title,
                'hero_desc'         => $request->hero_desc,
                'hero_btn_text'     => $request->hero_btn_text,
                'hero_image'        => $hero_image,

                // Section 2: Job Role
                'job_heading'       => $request->job_heading,

                // Section 3: Stats & Interaction
                'stats_title'       => $request->stats_title,
                'stats_desc'        => $request->stats_desc,
                'stats_image'       => $stats_image,
                'stat_emp_title'    => $request->stat_emp_title,

                // ডাইনামিক লেবেল (যেগুলো আপনি ইনপুট হিসেবে চাচ্ছেন)
                'stat_emp_label'    => $request->stat_emp_label,
                'stat_hours_label'  => $request->stat_hours_label,
                'stat_offices_label'=> $request->stat_offices_label,

                // ভ্যালু এবং ডেসক্রিপশন
                'stat_emp'          => $request->stat_emp,
                'stat_emp_desc'     => $request->stat_emp_desc,
                'stat_hours'        => $request->stat_hours,
                'stat_hours_desc'   => $request->stat_hours_desc,
                'stat_offices'      => $request->stat_offices,
                'stat_offices_desc' => $request->stat_offices_desc,

                // Section 4: Benefits
                'benefits_title'    => $request->benefits_title,
                'benefits_footer'   => $request->benefits_footer,
                'benefits_list'     => $request->benefits_list, // Blade থেকে আসা অ্যারে
            ];

            $finalData = [
                'page'     => $this->page,
                'section'  => $this->section,
                'type'     => $type,
                'metadata' => $metadata,
                'status'   => 'active',
                'slug'     => $section->slug ?? 'career-' . Str::random(10)
            ];

            if ($section) {
                $section->update($finalData);
                $msg = 'Career page updated successfully.';
            } else {
                CMS::create($finalData);
                $msg = 'Career page created successfully.';
            }

            Cache::forget('cms');
            return redirect()->back()->with('t-success', $msg);

        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
