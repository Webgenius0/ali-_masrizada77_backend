<?php

namespace App\Http\Controllers\Web\Backend\CMS\Web;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\CMS;
use Exception;
use App\Services\CmsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class EnergyandUtilitsController extends Controller
{
    protected $cmsService;
    public $page = "energyandutilitis";
    public $section = "main_content";
    public $uploadPath = 'cms/energy';

    public function __construct(CmsService $cmsService) {
        $this->cmsService = $cmsService;
    }

    public function index(Request $request) {
        $type = $request->query('type', 'english');
        $data = CMS::where('page', $this->page)->where('section', $this->section)->where('type', $type)->first();
        return view('backend.layouts.cms.energy.index', compact('data', 'type'));
    }

    public function content(Request $request) {
        try {
            $type = $request->get('type', 'english');
            $section = CMS::where('page', $this->page)->where('section', $this->section)->where('type', $type)->first();
            $oldMetadata = $section->metadata ?? [];

            // --- ফাইল হ্যান্ডলিং (Videos & Section 3 Image) ---
            $fileFields = ['sec1_video', 'sec2_video', 'sec3_image', 'sec5_video'];
            $metadata_files = [];
            foreach ($fileFields as $field) {
                $metadata_files[$field] = $oldMetadata[$field] ?? null;
                if ($request->hasFile($field)) {
                    if ($metadata_files[$field] && file_exists(public_path($metadata_files[$field]))) {
                        @unlink(public_path($metadata_files[$field]));
                    }
                    $metadata_files[$field] = Helper::fileUpload($request->file($field), $this->uploadPath, $field . '_' . time());
                }
            }

            // --- সেকশন ৩: Multiple Add (Icon+Title+Desc) ---
            $sec3_items = $request->sec3_items ?? [];
            foreach ($sec3_items as $key => $item) {
                $fileKey = "sec3_icon_{$key}";
                $existing = $oldMetadata['sec3_items'][$key]['icon'] ?? null;
                if ($request->hasFile($fileKey)) {
                    if ($existing && file_exists(public_path($existing))) { @unlink(public_path($existing)); }
                    $sec3_items[$key]['icon'] = Helper::fileUpload($request->file($fileKey), $this->uploadPath . '/sec3', "icon_{$key}_" . time());
                } else { $sec3_items[$key]['icon'] = $existing; }
            }

            // --- সেকশন ৪: Multiple Add (Icon+Title) ---
            $sec4_items = $request->sec4_items ?? [];
            foreach ($sec4_items as $key => $item) {
                $fileKey = "sec4_icon_{$key}";
                $existing = $oldMetadata['sec4_items'][$key]['icon'] ?? null;
                if ($request->hasFile($fileKey)) {
                    if ($existing && file_exists(public_path($existing))) { @unlink(public_path($existing)); }
                    $sec4_items[$key]['icon'] = Helper::fileUpload($request->file($fileKey), $this->uploadPath . '/sec4', "feat_{$key}_" . time());
                } else { $sec4_items[$key]['icon'] = $existing; }
            }

            // --- ফাইনাল মেটাডাটা ---
            $metadata = [
                'sec1_title' => $request->sec1_title, 'sec1_sub_title' => $request->sec1_sub_title, 'sec1_video' => $metadata_files['sec1_video'],
                'sec2_title' => $request->sec2_title, 'sec2_sub_title' => $request->sec2_sub_title, 'sec2_video' => $metadata_files['sec2_video'],
                'sec2_stats' => $request->sec2_stats, // 3 Stats Array
                'sec3_title' => $request->sec3_title, 'sec3_desc' => $request->sec3_desc, 'sec3_image' => $metadata_files['sec3_image'], 'sec3_items' => $sec3_items,
                'sec4_title' => $request->sec4_title, 'sec4_sub_title' => $request->sec4_sub_title, 'sec4_items' => $sec4_items,
                'sec5_title' => $request->sec5_title, 'sec5_desc' => $request->sec5_desc, 'sec5_video' => $metadata_files['sec5_video'],
                'sec6_title' => $request->sec6_title, 'sec6_desc' => $request->sec6_desc, 'sec6_faqs' => $request->sec6_faqs,
                'sec6_sub_title' => $request->sec6_sub_title, 'sec1_url_title'=>$request->sec1_url_title,'sec4_sub_desc'=>$request->sec4_sub_desc,
                'sec4_url_title'=>$request->sec4_url_title,
            ];

            CMS::updateOrCreate(
                ['page' => $this->page, 'section' => $this->section, 'type' => $type],
                ['metadata' => $metadata, 'status' => 'active', 'slug' => 'energy-' . $type]
            );

            Cache::forget('cms');
            return redirect()->back()->with('t-success', 'Healthcare content updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
