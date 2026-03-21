<?php

namespace App\Http\Controllers\Web\Backend\CMS\Web;
use App\Models\CMS;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplyJobController extends Controller
{
       public function index(Request $request)
    {
        // ল্যাঙ্গুয়েজ অনুযায়ী ডাটা ফিল্টার (Default: english)
        $type = $request->query('type', 'english');

        $data = CMS::where('slug', 'applyjob')
                   ->where('type', $type)
                   ->first();

        return view('backend.layouts.cms.applyjob', compact('data', 'type'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'contact_title' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'type'          => 'required|in:english,de',
        ]);

        // আপডেট অথবা ক্রিয়েট লজিক (Upsert)
        CMS::updateOrCreate(
            [
                'slug' => 'applyjob',
                'type' => $request->type, // ল্যাঙ্গুয়েজ অনুযায়ী খুঁজবে
            ],
            [
                'page'    => 'applyjob',
                'section' => 'sidebar',
                'metadata' => [
                    'contact_title' => $request->contact_title,
                    'contact_desc'  => $request->contact_desc,
                    'contact_email' => $request->contact_email,
                    'contact_phone' => $request->contact_phone,
                ]
            ]
        );

        return redirect()->back()->with('t-success', 'Apply job Content updated successfully for ' . ucfirst($request->type));
    }
}
