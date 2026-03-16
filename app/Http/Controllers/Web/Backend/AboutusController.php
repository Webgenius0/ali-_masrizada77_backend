<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use App\Helpers\Helper;
use App\Models\CMS;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class AboutusController extends Controller
{
    public $page;
    public $section;
    public $uploadPath;

    public function __construct()
    {
        $this->page = PageEnum::Aboutus;
        $this->section = SectionEnum::INTRO;
        $this->uploadPath = 'cms/aboutus';
    }

    public function index(Request $request)
    {
        $type = $request->get('type', 'english');
        $data = CMS::where('page', $this->page)
            ->where('section', $this->section)
            ->where('type', $type)
            ->first();

        return view("backend.layouts.cms.aboutus.aboutus", compact('data', 'type'));
    }

    public function content(Request $request)
    {
        try {
            $type = $request->get('type', 'english');
            $section = CMS::where('page', $this->page)
                ->where('section', $this->section)
                ->where('type', $type)
                ->first();

            $metadata = $section->metadata ?? [];

            // Hero Image logic
            $hero_image = $section->image1 ?? null;
            if ($request->hasFile('hero_image')) {
                $this->removeOldFile($hero_image);
                $hero_image = Helper::fileUpload($request->file('hero_image'), $this->uploadPath, 'hero_' . time());
            }

            // Section 3 Image logic
            $section3_image = $metadata['sec3_image'] ?? null;
            if ($request->hasFile('section3_image')) {
                $this->removeOldFile($section3_image);
                $section3_image = Helper::fileUpload($request->file('section3_image'), $this->uploadPath, 'sec3_' . time());
            }

            $finalMetadata = [
                'hero_title'    => $request->hero_title,
                'hero_subtitle' => $request->hero_subtitle,
                'sec2_title'    => $request->sec2_title,
                'sec2_subtitle' => $request->sec2_subtitle,
                'sec3_title'    => $request->sec3_title,
                'sec3_image'    => $section3_image,
                'sec3_img_desc' => $request->sec3_img_desc,
                'sec3_qa'       => $request->sec3_qa ?? [],
                'sec4_title'    => $request->sec4_title,
                'sec4_subtitle' => $request->sec4_subtitle,
                'sec5_title'    => $request->sec5_title,
                'sec5_subtitle' => $request->sec5_subtitle,
                'sec5_qa'       => $request->sec5_qa ?? [],
            ];

            $finalData = [
                'page'     => $this->page,
                'section'  => $this->section,
                'type'     => $type,
                'image1'   => $hero_image, // Fixed: Using image1 as per your DB
                'metadata' => $finalMetadata,
                'status'   => 'active',
                'slug'     => $section->slug ?? 'about_' . Str::random(10),
            ];

            if ($section) {
                $section->update($finalData);
            } else {
                CMS::create($finalData);
            }

            Cache::forget('cms');
            return redirect()->back()->with('t-success', 'About Us updated successfully');

        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }

    private function removeOldFile($path)
    {
        if (!empty($path) && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
