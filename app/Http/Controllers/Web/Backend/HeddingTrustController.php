<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HeddingTrustController extends Controller
{
    public function index(Request $request)
    {
        // ল্যাঙ্গুয়েজ অনুযায়ী ডাটা ফিল্টার (Default: english)
        $type = $request->query('type', 'english');

        $data = CMS::where('slug', 'trust_heading')
                   ->where('type', $type)
                   ->first();

        return view('backend.layouts.trust_heading.index', compact('data', 'type'));
    }

public function update(Request $request)
{
    // 1. Validate only what is actually in your form
    $request->validate([
        'type'          => 'required|in:english,de',
        'contact_title' => 'required|string|max:255',
        'contact_desc'  => 'nullable|string',
    ]);

    // 2. Update or Create
    CMS::updateOrCreate(
        [
            'slug' => 'trust_heading',
            'type' => $request->type,
        ],
        [
            'page'     => 'trust_heading',
            'section'  => 'get-in-touch',
            'metadata' => [
                'contact_title' => $request->contact_title,
                'contact_desc'  => $request->contact_desc,
                // Removed email and phone from here
            ]
        ]
    );

    return redirect()->back()->with('t-success', 'Content updated successfully!');
}
}
