<?php

namespace App\Http\Controllers\Web\Backend\CMS\Web;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\CMS;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class TrustController extends Controller
{
    public $page;
    public $section;
    public $uploadPath = 'cms/trust';

    public function __construct() {
        $this->page = PageEnum::Trust;
        $this->section = SectionEnum::TRUST;
    }

    public function index(Request $request) {
        $type = $request->query('type', 'english');
        $data = CMS::where('page', $this->page)
            ->where('section', $this->section)
            ->where('type', $type)
            ->first();

        return view('backend.layouts.cms.trust.index', compact('data', 'type'));
    }

    public function content(Request $request) {
        try {
            $type = $request->get('type', 'english');
            $section = CMS::where('page', $this->page)
                ->where('section', $this->section)
                ->where('type', $type)
                ->first();
            $oldMetadata = $section->metadata ?? [];

            // --- Hero Image ---
            $hero_image = $section->image1 ?? null;
            if ($request->hasFile('hero_image')) {
                if ($hero_image && file_exists(public_path($hero_image))) {
                    @unlink(public_path($hero_image));
                }
                $hero_image = Helper::fileUpload($request->file('hero_image'), $this->uploadPath, 'hero_' . time());
            }

            // --- Standards Items (Icon+Title+Desc+PDF) ---
            $standards_items = $request->standards_items ?? [];
            foreach ($standards_items as $key => $item) {
                // Icon handling
                $iconKey = "standards_icon_{$key}";
                $existingIcon = $oldMetadata['standards_items'][$key]['icon'] ?? null;
                if ($request->hasFile($iconKey)) {
                    if ($existingIcon && file_exists(public_path($existingIcon))) { @unlink(public_path($existingIcon)); }
                    $standards_items[$key]['icon'] = Helper::fileUpload($request->file($iconKey), $this->uploadPath . '/standards', "std_icon_{$key}_" . time());
                } else { 
                    $standards_items[$key]['icon'] = $existingIcon; 
                }

                // PDF handling
                $pdfKey = "standards_pdf_{$key}";
                $existingPdf = $oldMetadata['standards_items'][$key]['pdf'] ?? null;
                if ($request->hasFile($pdfKey)) {
                    if ($existingPdf && file_exists(public_path($existingPdf))) { @unlink(public_path($existingPdf)); }
                    $standards_items[$key]['pdf'] = Helper::fileUpload($request->file($pdfKey), $this->uploadPath . '/standards/pdfs', "std_pdf_{$key}_" . time());
                } else { 
                    $standards_items[$key]['pdf'] = $existingPdf; 
                }
            }

            // --- Protection Items (Icon+Title+Desc) ---
            $protection_items = $request->protection_items ?? [];
            foreach ($protection_items as $key => $item) {
                $fileKey = "protection_icon_{$key}";
                $existing = $oldMetadata['protection_items'][$key]['icon'] ?? null;
                if ($request->hasFile($fileKey)) {
                    if ($existing && file_exists(public_path($existing))) { @unlink(public_path($existing)); }
                    $protection_items[$key]['icon'] = Helper::fileUpload($request->file($fileKey), $this->uploadPath . '/protection', "prot_icon_{$key}_" . time());
                } else { 
                    $protection_items[$key]['icon'] = $existing; 
                }
            }

            $metadata = [
                'hero_title'     => $request->hero_title,
                'hero_desc'      => $request->hero_desc,
                'hero_btn_text'  => $request->hero_btn_text,
                'hero_btn_url'   => $request->hero_btn_url,
                
                'standards_title' => $request->standards_title,
                'standards_desc'  => $request->standards_desc,
                'standards_items'  => $standards_items,

                'protection_title' => $request->protection_title,
                'protection_desc'  => $request->protection_desc,
                'protection_items'  => $protection_items,
            ];

            CMS::updateOrCreate(
                ['page' => $this->page, 'section' => $this->section, 'type' => $type],
                [
                    'metadata' => $metadata, 
                    'image1'   => $hero_image,
                    'status'   => 'active', 
                    'slug'     => 'trust-' . $type
                ]
            );

            Cache::forget('cms');
            return redirect()->back()->with('t-success', 'Trust content updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
