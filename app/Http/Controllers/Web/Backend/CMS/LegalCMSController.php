<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use Illuminate\Http\Request;

class LegalCMSController extends Controller
{
    public function form($section)
    {
        $page = 'legal';
        $data_en = CMS::where('page', $page)
                    ->where('section', $section)
                    ->where('type', 'english')
                    ->first();

        $data_de = CMS::where('page', $page)
                    ->where('section', $section)
                    ->where('type', 'de')
                    ->first();

        return view('backend.layouts.cms.legalform', compact('page', 'section', 'data_en', 'data_de'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'page' => 'required',
            'section' => 'required',
            'description_en' => 'required',
            'description_de' => 'required',
            'title_en' => 'nullable|string',
            'sub_title_en' => 'nullable|string',
            'title_de' => 'nullable|string',
            'sub_title_de' => 'nullable|string',
        ]);

        // Save English version
        CMS::updateOrCreate(
            ['page' => $request->page, 'section' => $request->section, 'type' => 'english'],
            [
                'title' => $request->title_en,
                'sub_title' => $request->sub_title_en,
                'description' => $request->description_en,
                'status' => 'active',
            ]
        );

        // Save German version
        CMS::updateOrCreate(
            ['page' => $request->page, 'section' => $request->section, 'type' => 'de'],
            [
                'title' => $request->title_de,
                'sub_title' => $request->sub_title_de,
                'description' => $request->description_de,
                'status' => 'active',
            ]
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Legal document updated successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Document updated successfully!');
    }
}
