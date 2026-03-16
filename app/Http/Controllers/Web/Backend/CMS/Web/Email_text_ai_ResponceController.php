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

class Email_text_ai_ResponceController extends Controller
{
    protected $cmsService;
    public $page;
    public $section;
    public $uploadPath;

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
        $this->page = PageEnum::EmailAndTextAI;
        $this->section = SectionEnum::INTRO;
        $this->uploadPath = 'cms/emailandtextai';
    }
    public function index(Request $request)
    {
        $type = $request->get('type', 'english');

        $data = CMS::where('page', $this->page)
            ->where('section', $this->section)
            ->where('type', $type)
            ->where('status', 'active')
            ->first();

        return view("backend.layouts.cms.email_and_textai_response.email_and_textai_response", [
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

            // ১. হিরো ভিডিও (Input: video_file)
            $video = $section->video ?? null;
            if ($request->hasFile('video_file')) {
                $this->removeOldFile($video);
                $video = Helper::fileUpload($request->file('video_file'), $this->uploadPath . '/videos', 'hero_vid_' . time());
            }

            // ২. সেকশন ৩ ভিডিও (Input: bottom_video)
            $image4 = $section->image4 ?? null;

            if ($request->hasFile('sec3_image')) {
                // পুরানো ফাইল মুছে ফেলা
                $this->removeOldFile($image4);

                // নতুন image আপলোড
                $image4 = Helper::fileUpload(
                    $request->file('sec3_image'),
                    $this->uploadPath . '/images',          // images ফোল্ডারে রাখা ভালো
                    'sec3_img_' . time()
                );

                // কলামে সেভ
                $section->image4 = $image4;
            }

            // ৩. সেকশন ৪ ইমেজ (Input: image1 - আপনার রুলস অনুযায়ী)
            $sec4_image = $section->metadata['sec4_image'] ?? null;
            if ($request->hasFile('image1')) {
                $this->removeOldFile($sec4_image);
                $sec4_image = Helper::fileUpload($request->file('image1'), $this->uploadPath . '/videos', 'sec4_img_' . time());
            }

            // ৫. মেটাডেটা প্রিপারেশন
            $metadata = [
                'sec1_title'        => $request->sec1_title,
                'sec1_desc'         => $request->sec1_desc,
                'sec1_url_title'    => $request->sec1_url_title,
                'sec1_url_link'     => $request->sec1_url_link,
                'sec2_items'        => $request->sec2_items ?? [],
                'sec2_right_title'  => $request->sec2_right_title,
                'sec2_right_desc'   => $request->sec2_right_desc,
                'sec3_title'        => $request->sec3_title,
                'sec3_desc'         => $request->sec3_desc,
                'sec4_title'        => $request->sec4_title,
                'sec4_subtitle'     => $request->sec4_subtitle,
                'sec4_features'     => $request->sec4_features ?? [],
                'sec4_image'        => $sec4_image,
            ];

            $finalData = [
                'page'     => $this->page,
                'section'  => $this->section,
                'type'     => $type,
                'video'    => $video,
                'image4'   => $image4,
                'metadata' => $metadata,
                'status'   => 'active',
                'slug'     => $section->slug ?? 'conv_' . Str::random(10)
            ];

            if ($section) {
                $section->update($finalData);
            } else {
                CMS::create($finalData);
            }

            Cache::forget('cms');
            return redirect()->back()->with('t-success', 'Updated successfully');
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
