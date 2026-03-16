<?php

namespace App\Http\Controllers\Web\Backend\CMS\Web;

use App\Http\Controllers\Controller;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use App\Helpers\Helper;
use App\Models\CMS;
use Exception;
use App\Http\Requests\CmsRequest;
use App\Services\CmsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class NVPlatformOverviewController extends Controller
{
    protected $cmsService;
    public $page;
    public $section;
    public $uploadPath;

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
        $this->page = PageEnum::NVPlatformOverview;
        $this->section = SectionEnum::INTRO;
        $this->uploadPath = 'cms/NVPlatformOverview';
    }

    public function index(Request $request)
    {
        $type = $request->get('type', 'english');

        $data = CMS::where('page', $this->page)
            ->where('section', $this->section)
            ->where('type', $type)
            ->first();

        return view("backend.layouts.cms.NVPlatformOverview.NVPlatformOverview", [
            "data" => $data,
            "page" => $this->page->value,
            "section" => $this->section->value,
            "selected_type" => $type,
            "type" => $type
        ]);
    }

    public function content(CmsRequest $request)
    {
        try {
            $type = $request->get('type', 'english');

            // existing data based on page, section and language type
            $section = CMS::where('page', $this->page)
                ->where('section', $this->section)
                ->where('type', $type)
                ->first();

            $data = [];

            // --- 1. Main Table Image Handling (Hero Images) ---
            $imageFields = ['image1', 'image2', 'image3'];
            foreach ($imageFields as $field) {
                // ব্লেড ফাইলে ইনপুট নেম sec1_img1, sec1_img2.. কিন্তু ডাটাবেজ কলাম image1, image2..
                $inputName = "sec1_img" . substr($field, -1);

                if ($request->hasFile($inputName)) {
                    if ($section && $section->$field && file_exists(public_path($section->$field))) {
                        @unlink(public_path($section->$field));
                    }
                    $data[$field] = Helper::fileUpload($request->file($inputName), $this->uploadPath, time() . "_$field");
                } else {
                    $data[$field] = $section->$field ?? null;
                }
            }

            // --- 2. Info Section Video Handling (Saved in main table 'video' column) ---
            if ($request->hasFile('sec2_video')) {
                if ($section && $section->video && file_exists(public_path($section->video))) {
                    @unlink(public_path($section->video));
                }
                $data['video'] = Helper::fileUpload($request->file('sec2_video'), $this->uploadPath . '/video', time() . '_sec2_video');
            } else {
                $data['video'] = $section->video ?? null;
            }

            // --- 3. Metadata Preparation ---
            $metadata = [
                // Section 1: Hero
                'sec1_title'     => $request->sec1_title,
                'sec1_desc'      => $request->sec1_desc,
                'sec1_url_title' => $request->sec1_url_title,
                'sec1_url_link'  => $request->sec1_url_link,

                // Section 2: Info
                'sec2_left'        => $request->sec2_left ?? [], // Array of Title & Desc
                'sec2_right_title' => $request->sec2_right_title,
                'sec2_right_desc'  => $request->sec2_right_desc,

                // Section 3: Feature
                'sec3_title' => $request->sec3_title,
                'sec3_items' => $request->sec3_items ?? [],

                // Section 4 & 5: Static Image Sections
                'sec4_title'    => $request->sec4_title,
                'sec4_desc'     => $request->sec4_desc,
                'sec5_title'    => $request->sec5_title,
                'sec5_desc'     => $request->sec5_desc,
                'sec5_img_desc' => $request->sec5_img_desc,

                // Section 6: Gallery
                'sec6_title'    => $request->sec6_title,
                'sec6_subtitle' => $request->sec6_subtitle,
                'sec6_items'    => $request->sec6_items ?? [],

                // Section 7: CTA
                'sec7_title'     => $request->sec7_title,
                'sec7_desc'      => $request->sec7_desc,
                'sec7_url_title' => $request->sec7_url_title,
                'sec7_url_link'  => $request->sec7_url_link,
            ];

            // --- 4. Nested Image Handling inside Metadata ---

            // Sec 4 Image
            if ($request->hasFile('sec4_image')) {
                $metadata['sec4_image'] = Helper::fileUpload($request->file('sec4_image'), $this->uploadPath . '/sec4', time() . "_sec4");
            } else {
                $metadata['sec4_image'] = $section->metadata['sec4_image'] ?? null;
            }

            // Sec 5 Image
            if ($request->hasFile('sec5_image')) {
                $metadata['sec5_image'] = Helper::fileUpload($request->file('sec5_image'), $this->uploadPath . '/sec5', time() . "_sec5");
            } else {
                $metadata['sec5_image'] = $section->metadata['sec5_image'] ?? null;
            }

            // Sec 7 Image
            if ($request->hasFile('sec7_image')) {
                $metadata['sec7_image'] = Helper::fileUpload($request->file('sec7_image'), $this->uploadPath . '/sec7', time() . "_sec7");
            } else {
                $metadata['sec7_image'] = $section->metadata['sec7_image'] ?? null;
            }

            // Sec 6 Gallery Images Handling
            if ($request->has('sec6_items')) {
                $gallery_items = [];
                foreach ($request->sec6_items as $key => $item) {
                    $existing_gallery_img = $section->metadata['sec6_items'][$key]['image'] ?? null;
                    if ($request->hasFile("sec6_items.$key.image")) {
                        $img_path = Helper::fileUpload($request->file("sec6_items.$key.image"), $this->uploadPath . '/gallery', time() . "_gal_$key");
                    } else {
                        $img_path = $existing_gallery_img;
                    }
                    $gallery_items[$key] = [
                        'title' => $item['title'] ?? '',
                        'desc'  => $item['desc'] ?? '',
                        'image' => $img_path
                    ];
                }
                $metadata['sec6_items'] = $gallery_items;
            }

            // --- 5. Final Store / Update Logic ---
            $finalData = [
                'page'      => $this->page,
                'section'   => $this->section,
                'type'      => $type,
                'image1'    => $data['image1'],
                'image2'    => $data['image2'],
                'image3'    => $data['image3'],
                'video'     => $data['video'],
                'metadata'  => $metadata,
                'status'    => 'active',
                'slug'      => $section->slug ?? 'nvpo_' . Str::random(10)
            ];

            if ($section) {
                $section->update($finalData);
                $msg = ucfirst($type) . ' content updated successfully.';
            } else {
                CMS::create($finalData);
                $msg = ucfirst($type) . ' content stored successfully.';
            }

            Cache::forget('cms');
            return redirect()->route("admin.cms.home.npoverview.index", ['type' => $type])->with('t-success', $msg);

        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
