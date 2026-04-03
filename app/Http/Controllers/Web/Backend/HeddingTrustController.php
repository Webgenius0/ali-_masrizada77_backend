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
        $request->validate([
            'contact_title' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'type'          => 'required|in:english,de', // ল্যাঙ্গুয়েজ ভ্যালিডেশন
        ]);

        // আপডেট অথবা ক্রিয়েট লজিক (Upsert)
        CMS::updateOrCreate(
            [
                'slug' => 'trust_heading',
                'type' => $request->type, // ল্যাঙ্গুয়েজ অনুযায়ী খুঁজবে
            ],
            [
                'page'    => 'trust_heading',
                'section' => 'sidebar', // এই ভ্যালুটা না দিলে এরর আসবে
                'metadata' => [
                    'contact_title' => $request->contact_title,
                    'contact_desc'  => $request->contact_desc,
                    'contact_email' => $request->contact_email,
                    'contact_phone' => $request->contact_phone,
                ]
            ]
        );

        return redirect()->back()->with('t-success', 'Content updated successfully for ' . ucfirst($request->type));
    }
}
