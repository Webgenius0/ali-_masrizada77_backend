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

class InfrastructureController extends Controller
{
    protected $cmsService;
    public $page;
    public $section;
    public $uploadPath;

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
        // নিশ্চিত হয়ে নিন PageEnum ও SectionEnum এ এই ভ্যালুগুলো আছে, না থাকলে স্ট্রিং হিসেবে দিতে পারেন
        $this->page = PageEnum::Infrastructure ?? 'infrastructure';
        $this->section = SectionEnum::INTRO ?? 'MAIN';
        $this->uploadPath = 'cms/infrastructure';
    }

    public function index(Request $request)
    {
       $type = $request->query('type', 'english');

    $data = CMS::where('page', $this->page)
               ->where('section', $this->section)
               ->where('type', $type)
               ->first();

    return view('backend.layouts.cms.infrastructure.index', compact('data', 'type'));
    }

    public function content(Request $request)
    {
        try {
            $type = $request->get('type', 'english');
            $section = CMS::where('page', $this->page)
                ->where('section', $this->section)
                ->where('type', $type)
                ->first();

            // --- ১. সেকশন ২ এর ৩টি ক্যাটাগরি ইমেজ হ্যান্ডলিং ---
            $sec2_images = $section->metadata['sec2_images'] ?? [null, null, null];
            for ($i = 0; $i < 3; $i++) {
                if ($request->hasFile("sec2_img_$i")) {
                    if (!empty($sec2_images[$i]) && file_exists(public_path($sec2_images[$i]))) {
                        @unlink(public_path($sec2_images[$i]));
                    }
                    $sec2_images[$i] = Helper::fileUpload($request->file("sec2_img_$i"), $this->uploadPath . '/cards', "card_$i" . time());
                }
            }

            // --- ২. সেকশন ৩ এর ফিচার ইমেজ (আইকন) হ্যান্ডলিং ---
            $sec3_deployments = $request->sec3_deployments ?? [];
            if ($request->has('sec3_deployments')) {
                foreach ($sec3_deployments as $key => $deployment) {
                    if (isset($deployment['features'])) {
                        foreach ($deployment['features'] as $fIdx => $feature) {
                            $fileKey = "sec3_feat_img_{$key}_{$fIdx}";
                            $existing_icon = $section->metadata['sec3_deployments'][$key]['features'][$fIdx]['icon'] ?? null;

                            if ($request->hasFile($fileKey)) {
                                if ($existing_icon && file_exists(public_path($existing_icon))) {
                                    @unlink(public_path($existing_icon));
                                }
                                $sec3_deployments[$key]['features'][$fIdx]['icon'] = Helper::fileUpload($request->file($fileKey), $this->uploadPath . '/features', "feat_{$key}_{$fIdx}_" . time());
                            } else {
                                $sec3_deployments[$key]['features'][$fIdx]['icon'] = $existing_icon;
                            }
                        }
                    }
                }
            }

            // --- ৩. মেটাডাটা প্রিপারেশন ---
            $metadata = [
                'sec1_title'     => $request->sec1_title,
                'sec1_sub_title' => $request->sec1_sub_title,
                'sec2_data'      => $request->sec2_data,
                'sec2_images'    => $sec2_images,
                'sec3_deployments' => $sec3_deployments,
                'table_rows'     => $request->table_rows,
                'faq_title'      => $request->faq_title,
                'faq_sub_title'  => $request->faq_sub_title,
                'faqs'           => $request->faqs,
            ];

            $finalData = [
                'page'     => $this->page,
                'section'  => $this->section,
                'type'     => $type,
                'metadata' => $metadata,
                'status'   => 'active',
                'slug'     => $section->slug ?? 'infra-' . Str::random(10)
            ];

            if ($section) {
                $section->update($finalData);
                $msg = 'Infrastructure content updated successfully.';
            } else {
                CMS::create($finalData);
                $msg = 'Infrastructure content stored successfully.';
            }

            Cache::forget('cms');
            return redirect()->back()->with('t-success', $msg);

        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
