<?php

namespace App\Http\Controllers\Web\Backend\CMS\Web;

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

class PartnerController extends Controller
{
    protected $cmsService;
    public $page;
    public $section;
    public $uploadPath;

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
        // PageEnum ও SectionEnum এ Partner না থাকলে স্ট্রিং হিসেবে কাজ করবে
        $this->page = PageEnum::Partner;
        $this->section = SectionEnum::INTRO;
        $this->uploadPath = 'cms/partner';
    }

    public function index(Request $request)
    {
        $type = $request->query('type', 'english');

        $data = CMS::where('page', $this->page)
                   ->where('section', $this->section)
                   ->where('type', $type)
                   ->first();

        return view('backend.layouts.cms.partner.index', compact('data', 'type'));
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

            // --- ১. সেকশন ১: হিরো ইমেজ হ্যান্ডলিং ---
            $sec1_image = $oldMetadata['sec1_image'] ?? null;
            if ($request->hasFile('sec1_image')) {
                if ($sec1_image && file_exists(public_path($sec1_image))) {
                    @unlink(public_path($sec1_image));
                }
                $sec1_image = Helper::fileUpload($request->file('sec1_image'), $this->uploadPath, 'hero_' . time());
            }

            // --- ২. সেকশন ২: ইকোসিস্টেম (Multiple Logos with Links) ---
$ecosystem = $request->ecosystem ?? [];
if ($request->has('ecosystem')) {
    foreach ($ecosystem as $key => $item) {
        $fileKey = "eco_img_{$key}";
        $existing_logo = $oldMetadata['ecosystem'][$key]['image'] ?? null;

        if ($request->hasFile($fileKey)) {
            if ($existing_logo && file_exists(public_path($existing_logo))) {
                @unlink(public_path($existing_logo));
            }
            $ecosystem[$key]['image'] = Helper::fileUpload($request->file($fileKey), $this->uploadPath . '/ecosystem', "eco_{$key}_" . time());
        } else {
            $ecosystem[$key]['image'] = $existing_logo;
        }
    }
}

            // --- ৩. সেকশন ৩: ফিচার (Icon + Title + Desc) ---
            $features = $request->features ?? [];
            if ($request->has('features')) {
                foreach ($features as $key => $feat) {
                    $fileKey = "feat_icon_{$key}";
                    $existing_icon = $oldMetadata['features'][$key]['icon'] ?? null;

                    if ($request->hasFile($fileKey)) {
                        if ($existing_icon && file_exists(public_path($existing_icon))) {
                            @unlink(public_path($existing_icon));
                        }
                        $features[$key]['icon'] = Helper::fileUpload($request->file($fileKey), $this->uploadPath . '/features', "feat_{$key}_" . time());
                    } else {
                        $features[$key]['icon'] = $existing_icon;
                    }
                }
            }

            // --- ৪. সেকশন ৪: FAQ ইমেজ হ্যান্ডলিং ---
            $faq_image = $oldMetadata['faq_image'] ?? null;
            if ($request->hasFile('faq_image')) {
                if ($faq_image && file_exists(public_path($faq_image))) {
                    @unlink(public_path($faq_image));
                }
                $faq_image = Helper::fileUpload($request->file('faq_image'), $this->uploadPath, 'faq_' . time());
            }

            // --- ৫. সেকশন ৫: Who We Partner With (Title, Desc & Image) ---
            $who_image = $oldMetadata['who_image'] ?? null;
            if ($request->hasFile('who_image')) {
                if ($who_image && file_exists(public_path($who_image))) {
                    @unlink(public_path($who_image));
                }
                $who_image = Helper::fileUpload($request->file('who_image'), $this->uploadPath, 'who_' . time());
            }

            // --- মেটাডাটা প্রিপারেশন ---
            $metadata = [
                'sec1_title'     => $request->sec1_title,
                'sec1_sub_title' => $request->sec1_sub_title,
                'sec1_image'     => $sec1_image,
                'eco_title'      => $request->eco_title,
                'eco_sub_title'  => $request->eco_sub_title,
                'ecosystem'      => $ecosystem,
                'features'       => $features,
                'faq_title'      => $request->faq_title,
                'faq_image'      => $faq_image,
                'faqs'           => $request->faqs,

                // নতুন ডাটা
                'who_title'      => $request->who_title,
                'who_desc'       => $request->who_desc,
                'who_image'      => $who_image,
                'extra_faq_title'=> $request->extra_faq_title,
                'extra_faq_sub'  => $request->extra_faq_sub,
                'extra_faqs'     => $request->extra_faqs, // Multiple Q&A Array
            ];

            $finalData = [
                'page'     => $this->page,
                'section'  => $this->section,
                'type'     => $type,
                'metadata' => $metadata,
                'status'   => 'active',
                'slug'     => $section->slug ?? 'partner-' . Str::random(10)
            ];

            if ($section) {
                $section->update($finalData);
                $msg = 'Partner page updated successfully.';
            } else {
                CMS::create($finalData);
                $msg = 'Partner page stored successfully.';
            }

            Cache::forget('cms');
            return redirect()->back()->with('t-success', $msg);

        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
