<?php

namespace App\Http\Controllers\Web\Backend\CMS\Web;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public $page;
    public $section;

    public function __construct() {
        $this->page = PageEnum::ANNOUNCEMENT;
        $this->section = SectionEnum::ANNOUNCEMENT;
    }

    public function index(Request $request) {
        $data_en = CMS::where('page', $this->page)
            ->where('section', $this->section)
            ->where('type', 'english')
            ->first();

        $data_de = CMS::where('page', $this->page)
            ->where('section', $this->section)
            ->where('type', 'de')
            ->first();

        return view('backend.layouts.cms.announcement.index', compact('data_en', 'data_de'));
    }

    public function update(Request $request) {
        try {
            // Save English version
            CMS::updateOrCreate(
                ['page' => $this->page, 'section' => $this->section, 'type' => 'english'],
                [
                    'title' => $request->title_en,
                    'btn_text' => $request->btn_text_en,
                    'btn_link' => $request->btn_link_en,
                    'status' => 'active',
                ]
            );

            // Save German version
            CMS::updateOrCreate(
                ['page' => $this->page, 'section' => $this->section, 'type' => 'de'],
                [
                    'title' => $request->title_de,
                    'btn_text' => $request->btn_text_de,
                    'btn_link' => $request->btn_link_de,
                    'status' => 'active',
                ]
            );

            Cache::forget('cms');
            return redirect()->back()->with('t-success', 'Announcement updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
