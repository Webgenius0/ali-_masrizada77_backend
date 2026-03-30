<?php

namespace App\Http\Controllers\Web\Backend\CMS\Web\Home;

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

class HomeIntroController extends Controller
{
    protected $cmsService;
    public $page;
    public $section;
    public $uploadPath;

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
        $this->page = PageEnum::HOME;
        $this->section = SectionEnum::INTRO;
        $this->uploadPath = 'cms/home/intro';
    }

    public function index(Request $request)
    {
        $type = $request->get('type', 'english');

        $data = CMS::where('page', $this->page)
            ->where('section', $this->section)
            ->where('type', $type)
            ->first();

        return view("backend.layouts.cms.index", [
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

            // ১. সংশোধিত লজিক: ডাইনামিক টাইপ অনুযায়ী existing ডেটা খোঁজা
            $section = CMS::where('page', $this->page)
                ->where('section', $this->section)
                ->where('type', $type)
                ->first();

            $data = [];
            // --- Image Handling ---
            $imageFields = ['bg', 'image1', 'image2', 'image3'];
            foreach ($imageFields as $field) {
                if ($request->hasFile($field)) {
                    if ($section && $section->$field && file_exists(public_path($section->$field))) {
                        @unlink(public_path($section->$field));
                    }
                    $data[$field] = Helper::fileUpload($request->file($field), $this->uploadPath, time() . "_$field");
                } else {
                    $data[$field] = $section->$field ?? null;
                }
            }

            // Video Handling
            $data['video'] = $request->hasFile('video_file')
                ? Helper::fileUpload($request->file('video_file'), $this->uploadPath, time() . '_hero')
                : ($section->video ?? null);

            $data['image4'] = $request->hasFile('bottom_video')
                ? Helper::fileUpload($request->file('bottom_video'), $this->uploadPath, time() . '_bottom')
                : ($section->image4 ?? null);


$metaImages = [];

$metaImageFields = ['logo_img1', 'logo_img2'];

foreach ($metaImageFields as $field) {
    if ($request->hasFile($field)) {

        $oldImage = $section->metadata[$field] ?? null;

        if ($oldImage && file_exists(public_path($oldImage))) {
            @unlink(public_path($oldImage));
        }

        $metaImages[$field] = Helper::fileUpload(
            $request->file($field),
            $this->uploadPath . '/meta',
            time() . "_$field"
        );

    } else {
        $metaImages[$field] = $section->metadata[$field] ?? null;
    }
}



            // ২. Metadata Preparation
$metadata = [

//headr data
     'header_text'=>$request->header_text,
    // Hero Section Buttons
    'btn2_text'            => $request->btn2_text,
    'btn2_link'            => $request->btn2_link,

    // Info Section (Section 2)
    'sec2_title'           => $request->sec2_title,
    'sec2_short'           => $request->sec2_short,
    'sec2_items'           => $request->sec2_items, // Title + Desc array
    'sec2_link_title'      => $request->sec2_link_title,
    'sec2_link_url'        => $request->sec2_link_url,

    // Feature Section (Section 3)
    'feature_title'        => $request->feature_title,
    'feature_short'        => $request->feature_short,
    'feature_list'         => $request->feature_list,

    // AI Powered CX (Section 4 & 5)
    'cx_title'             => $request->cx_title,
    'cx_description'       => $request->cx_description,
    'cx_link_title'        => $request->cx_link_title,
    'cx_link_add'          => $request->cx_link_add,
    'cx_features'          => $request->cx_items, // Note: your blade uses cx_items

    // Case Study & Industry (Section 6)
    'case_sec_title'       => $request->case_sec_title,
    'case_sec_subtitle'    => $request->case_sec_subtitle,
    'case_image_subtile'   => $request->case_image_subtile,
    'case_description'     => $request->case_description,
    'industry_description' => $request->industry_description,
        'stat_1_val'  => $request->stat_1_val,
    'stat_1_text' => $request->stat_1_text,

    'stat_2_val'  => $request->stat_2_val,
    'stat_2_text' => $request->stat_2_text,

    'stat_3_val'  => $request->stat_3_val,
    'stat_3_text' => $request->stat_3_text,

    // Bottom Section & FAQ
    'bottom_desc'          => $request->bottom_desc,
    'bottom_btn_title'     => $request->bottom_btn_title,
    'bottom_btn_link'      => $request->bottom_btn_link,
    //AI agent like FAQ
// AI agent section
'ai_agents_title'       => $request->ai_agents_title,
'ai_agents_discription' => $request->ai_agents_discription,
'ai_deployment'         => $request->ai_deployment, // <--- এই লাইনটি অবশ্যই যোগ করতে হবে
    //FAQ
    'faq_title'            => $request->faq_title,
    'faq_discription' => $request->faq_discription,
    'faq'                  => $request->faq,

    //last better cx
    'last_bettercx_title'=>$request->last_bettercx_title,
    'last_bettercx_desc'=>$request->last_bettercx_desc,
// logo session 2
'logo_img1' => $metaImages['logo_img1'] ?? null,
'logo_img2' => $metaImages['logo_img2'] ?? null,

];

            // CX Features Handling (Image handling fixed for existing images)
            $cx_features = [];
            if ($request->has('cx_items')) {
                foreach ($request->cx_items as $key => $item) {
                    $existing_img = $section->metadata['cx_features'][$key]['img_path'] ?? null;

                    if ($request->hasFile("cx_items.$key.image")) {
                        if ($existing_img && file_exists(public_path($existing_img))) {
                            @unlink(public_path($existing_img));
                        }
                        $img_path = Helper::fileUpload($request->file("cx_items.$key.image"), $this->uploadPath . '/cx', time() . "_cx_$key");
                    } else {
                        $img_path = $existing_img;
                    }

                    $cx_features[$key] = [
                        'title'    => $item['title'] ?? '',
                        'desc'     => $item['desc'] ?? '',
                        'img_path' => $img_path
                    ];
                }
            }
            $metadata['cx_features'] = $cx_features;

            // Industry Items Handling
            if ($request->has('industry')) {
                $industry_items = [];
                foreach ($request->industry as $key => $item) {
                    $existing_industry = $section->metadata['industry_items'][$key] ?? null;
                    $img_path = $existing_industry['img'] ?? null;

                    if ($request->hasFile("industry.$key.image")) {
                        if ($img_path && file_exists(public_path($img_path))) {
                            @unlink(public_path($img_path));
                        }
                        $img_path = Helper::fileUpload($request->file("industry.$key.image"), $this->uploadPath . '/industry', time() . "_ind_$key");
                    }

                    $industry_items[$key] = [
                        'title' => $item['title'] ?? '',
                        'img'   => $img_path
                    ];
                }
                $metadata['industry_items'] = $industry_items;
            }

            // ৩. Store/Update Logic
            $finalData = [
                'page'      => $this->page,
                'section'   => $this->section,
                'type'      => $type,
                'title'     => $request->title,
                'sub_title' => $request->sub_title,
                'btn_text'  => $request->btn_text,
                'btn_link'  => $request->btn_link,
                'bg'        => $data['bg'],
                'image1'    => $data['image1'],
                'image2'    => $data['image2'],
                'image3'    => $data['image3'],
                'image4'    => $data['image4'],
                'video'     => $data['video'],
                'metadata'  => $metadata,
                'status'    => 'active',
                'slug'      => $section->slug ?? 'intro_' . Str::random(10)
            ];

            // টাইপ নির্বিশেষে যদি সেকশন থাকে তবে আপডেট হবে
            if ($section) {
                $section->update($finalData);
                $msg = ucfirst($type) . ' content updated successfully.';
            } else {
                CMS::create($finalData);
                $msg = ucfirst($type) . ' content stored as new entry.';
            }

            Cache::forget('cms');
            return redirect()->route("admin.cms.home.intro.index", ['type' => $type])->with('t-success', $msg);

        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
