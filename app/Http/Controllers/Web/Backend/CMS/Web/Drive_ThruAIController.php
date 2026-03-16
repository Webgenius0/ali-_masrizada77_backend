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
use Illuminate\Support\Facades\File;

class Drive_ThruAIController extends Controller
{
       protected $cmsService;
    public $page;
    public $section;
    public $uploadPath;

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
        $this->page = PageEnum::Drive_ThruAIController;
        $this->section = SectionEnum::INTRO;
        $this->uploadPath = 'cms/drive_thruai';
    }
    public function index(Request $request)
    {
        $type = $request->get('type', 'english');

        $data = CMS::where('page', $this->page)
            ->where('section', $this->section)
            ->where('type', $type)
            ->where('status', 'active')
            ->first();

        return view("backend.layouts.cms.drive_thruai.drive_thruai", [
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
        $section = CMS::where('page', $this->page)
            ->where('section', $this->section)
            ->where('type', $type)
            ->first();

        // ১. Hero video
        $video = $section->video ?? null;
        if ($request->hasFile('video_file')) {
            $this->removeOldFile($video);
            $video = Helper::fileUpload(
                $request->file('video_file'),
                $this->uploadPath . '/videos',
                'hero_vid_' . time()
            );
        }

        // ২. Section 3 image
        $image4 = $section->image4 ?? null;
        if ($request->hasFile('sec3_image')) {
            $this->removeOldFile($image4);
            $image4 = Helper::fileUpload(
                $request->file('sec3_image'),
                $this->uploadPath . '/images',
                'sec3_img_' . time()
            );
        }

        // ৩. Section 4 Main Image (image1 input)
        $sec4_image = $section->metadata['sec4_image'] ?? null;
        if ($request->hasFile('image1')) {
            $this->removeOldFile($sec4_image);
            $sec4_image = Helper::fileUpload(
                $request->file('image1'),
                $this->uploadPath . '/images',
                'sec4_main_img_' . time()
            );
        }

        // ৪. Dynamic sec4_features – icon image হ্যান্ডেলিং
        $sec4_features = [];
        // সরাসরি ইনপুট থেকে ফিচারগুলো নিন
        $inputFeatures = $request->input('sec4_features', []);

        foreach ($inputFeatures as $index => $item) {
            // ডিফল্টভাবে পুরাতন আইকনটি রাখা হলো
            $iconPath = $item['old_icon_image'] ?? null;

            // চেক করুন এই ইনডেক্সে নতুন কোনো ফাইল আপলোড হয়েছে কি না
            if ($request->hasFile("sec4_features.$index.icon_image")) {
                // নতুন ফাইল আপলোড করার আগে পুরাতন ফাইলটি ডিলিট করুন
                if (!empty($iconPath)) {
                    $this->removeOldFile($iconPath);
                }

                $file = $request->file("sec4_features.$index.icon_image");
                $iconPath = Helper::fileUpload(
                    $file,
                    $this->uploadPath . '/icons',
                    'feature_icon_' . time() . '_' . $index
                );
            }

            // ফাইনাল অ্যারে তৈরি (পুরাতন বা নতুন আইকন সহ)
            $sec4_features[] = [
                'icon_image' => $iconPath,
                'title'      => $item['title'] ?? '',
                'content'    => $item['content'] ?? '',
            ];
        }

        // ৫. Metadata প্রিপারেশন
        $metadata = [
            'sec1_title'        => $request->input('sec1_title', ''),
            'sec1_desc'         => $request->input('sec1_desc', ''),
            'sec1_url_title'    => $request->input('sec1_url_title', ''),
            'sec1_url_link'     => $request->input('sec1_url_link', ''),
            'sec2_items'        => $request->input('sec2_items', []),
            'sec2_right_title'  => $request->input('sec2_right_title', ''),
            'sec2_right_desc'   => $request->input('sec2_right_desc', ''),
            'sec3_title'        => $request->input('sec3_title', ''),
            'sec3_desc'         => $request->input('sec3_desc', ''),
            'sec4_title'        => $request->input('sec4_title', ''),
            'sec4_subtitle'     => $request->input('sec4_subtitle', ''),
            'sec4_features'     => $sec4_features,
            'sec4_image'        => $sec4_image,
        ];

        // ৬. Final data to save/update
        $finalData = [
            'page'     => $this->page,
            'section'  => $this->section,
            'type'     => $type,
            'video'    => $video,
            'image4'   => $image4,
            'metadata' => $metadata,
            'status'   => 'active',
            'slug'     => $section->slug ?? 'drv_thru_' . Str::random(10),
        ];

        if ($section) {
            $section->update($finalData);
        } else {
            CMS::create($finalData);
        }

        Cache::forget('cms');

        return redirect()->back()->with('t-success', 'Content updated successfully');
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
