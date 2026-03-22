<?php

namespace App\Http\Controllers\Web\Backend\CMS\Web;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\CMS;
use Exception;
use App\Services\CmsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class FinancialServicesController extends Controller
{
    protected $cmsService;
    public $page = "financialservices";
    public $section = "main_content";
    public $uploadPath = 'cms/financial';

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
    }

    public function index(Request $request)
    {
        $type = $request->query('type', 'english');
        $data = CMS::where('page', $this->page)->where('section', $this->section)->where('type', $type)->first();
        return view('backend.layouts.cms.financial.index', compact('data', 'type'));
    }

    public function content(Request $request)
    {
        try {
            $type = $request->get('type', 'english');
            $section = CMS::where('page', $this->page)->where('section', $this->section)->where('type', $type)->first();
            $oldMetadata = $section->metadata ?? [];

            // --- জেনারেল ফাইল হ্যান্ডলিং (Videos & Images) ---
            // ব্লেড ফাইল অনুযায়ী সেকশন ১ এ ৩টি ভিডিও এবং সেকশন ৩ এ একটি ইমেজ আছে
            $fileFields = ['sec1_video_1', 'sec1_video_2', 'sec1_video_3', 'sec2_video','sec2_image', 'sec3_image', 'sec5_video'];
            $metadata_files = [];

            foreach ($fileFields as $field) {
                $metadata_files[$field] = $oldMetadata[$field] ?? null;
                if ($request->hasFile($field)) {
                    // আগের ফাইল ডিলিট করা
                    if ($metadata_files[$field] && file_exists(public_path($metadata_files[$field]))) {
                        @unlink(public_path($metadata_files[$field]));
                    }
                    // নতুন ফাইল আপলোড
                    $metadata_files[$field] = Helper::fileUpload($request->file($field), $this->uploadPath, $field . '_' . time());
                }
            }

            // --- সেকশন ৩: ডাইনামিক আইটেমস (Icon + Title + Desc) ---
            $sec3_items = $request->sec3_items ?? [];
            foreach ($sec3_items as $key => $item) {
                $fileKey = "sec3_icon_{$key}";
                $existingIcon = $oldMetadata['sec3_items'][$key]['icon'] ?? null;

                if ($request->hasFile($fileKey)) {
                    if ($existingIcon && file_exists(public_path($existingIcon))) {
                        @unlink(public_path($existingIcon));
                    }
                    $sec3_items[$key]['icon'] = Helper::fileUpload($request->file($fileKey), $this->uploadPath . '/sec3', "icon_{$key}_" . time());
                } else {
                    $sec3_items[$key]['icon'] = $existingIcon;
                }
            }

            // --- সেকশন ৪: ডাইনামিক ফিচারস (Icon + Title) ---
            $sec4_items = $request->sec4_items ?? [];
            foreach ($sec4_items as $key => $item) {
                $fileKey = "sec4_icon_{$key}";
                $existingIcon = $oldMetadata['sec4_items'][$key]['icon'] ?? null;

                if ($request->hasFile($fileKey)) {
                    if ($existingIcon && file_exists(public_path($existingIcon))) {
                        @unlink(public_path($existingIcon));
                    }
                    $sec4_items[$key]['icon'] = Helper::fileUpload($request->file($fileKey), $this->uploadPath . '/sec4', "feat_{$key}_" . time());
                } else {
                    $sec4_items[$key]['icon'] = $existingIcon;
                }
            }

            // --- make meta data ---
            $metadata = [
                // Section 1
                'sec1_title'     => $request->sec1_title,
                'sec1_sub_title' => $request->sec1_sub_title,
                'sec1_video_1'   => $metadata_files['sec1_video_1'],
                'sec1_video_2'   => $metadata_files['sec1_video_2'],
                'sec1_video_3'   => $metadata_files['sec1_video_3'],

                // Section 2
                'sec2_title'     => $request->sec2_title,
                'sec2_sub_title' => $request->sec2_sub_title,
                'sec2_video'     => $metadata_files['sec2_video'],
                'sec2_stats'     => $request->sec2_stats,
                // new add
                'sec2_image' => $metadata_files['sec2_image'] ?? $oldMetadata['sec2_image'] ?? null,
                'sec2_desc'  => $request->sec2_desc,

                // Section 3
                'sec3_title'     => $request->sec3_title,
                'sec3_desc'      => $request->sec3_desc,
                'sec3_image'     => $metadata_files['sec3_image'],
                'sec3_items'     => $sec3_items,

                // Section 4
                'sec4_title'     => $request->sec4_title,
                'sec4_sub_title' => $request->sec4_sub_title,
                'sec4_items'     => $sec4_items,

                // Section 5
                'sec5_title'     => $request->sec5_title,
                'sec5_desc'      => $request->sec5_desc,
                'sec5_video'     => $metadata_files['sec5_video'],

                // Section 6
                'sec6_title'     => $request->sec6_title,
                'sec6_sub_title' => $request->sec6_sub_title,
                'sec6_faqs'      => $request->sec6_faqs,
            ];


            CMS::updateOrCreate(
                ['page' => $this->page, 'section' => $this->section, 'type' => $type],
                ['metadata' => $metadata, 'status' => 'active', 'slug' => 'financial_services' . $type]
            );

            Cache::forget('cms');
            return redirect()->back()->with('t-success', 'Financial Services Content updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
