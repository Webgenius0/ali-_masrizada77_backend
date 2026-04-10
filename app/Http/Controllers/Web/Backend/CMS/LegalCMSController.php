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
        $data = CMS::where('page', $page)
                    ->where('section', $section)
                    ->first();

        return view('backend.layouts.cms.legalform', compact('page', 'section', 'data'));
    }

public function store(Request $request)
{
    $request->validate([
        'page' => 'required',
        'section' => 'required',
        'description' => 'required',
    ]);

    CMS::updateOrCreate(
        ['page' => $request->page, 'section' => $request->section],
        [
            'description' => $request->description,
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
