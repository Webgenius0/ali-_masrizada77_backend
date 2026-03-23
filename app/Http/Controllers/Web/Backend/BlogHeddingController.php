<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BlogHeddingController extends Controller
{
public function index(Request $request) {
    $type = $request->type ?? 'english';

    // নির্দিষ্ট পেজ, সেকশন এবং ল্যাঙ্গুয়েজ টাইপ অনুযায়ী ডাটা আনা
    $data = CMS::where('page', 'blog')
               ->where('section', 'blog_heading')
               ->where('type', $type)
               ->first();

    return view('backend.layouts.blog_heading.index', compact('data'));
}

public function store(Request $request) {
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required',
        'image1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // নির্দিষ্ট ল্যাঙ্গুয়েজ টাইপ অনুযায়ী ডাটা খুঁজে বের করা বা নতুন অবজেক্ট তৈরি
    $data = CMS::where('page', $request->page)
               ->where('section', $request->section)
               ->where('type', $request->type)
               ->first() ?: new CMS();

    $data->page = $request->page;
    $data->section = $request->section;
    $data->type = $request->type;
    $data->title = $request->title;
    $data->description = $request->description;

    if ($request->hasFile('image1')) {
        // পুরনো ফাইল ডিলিট
        if ($data->image1 && File::exists(public_path($data->image1))) {
            File::delete(public_path($data->image1));
        }

        $file = $request->file('image1');
        $filename = time() . '_' . $request->type . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/cms/'), $filename);
        $data->image1 = 'uploads/cms/' . $filename;
    }

    $data->save();

    return redirect()->back()->with('t-success', strtoupper($request->type) . ' content updated successfully!');
}

    // এডিট করার জন্য AJAX ডাটা ফেচ
    public function edit($id) {
        $cms = CMS::findOrFail($id);
        return response()->json($cms);
    }

    public function destroy($id) {
        $data = CMS::findOrFail($id);
        if ($data->image1 && File::exists(public_path($data->image1))) {
            File::delete(public_path($data->image1));
        }
        $data->delete();
        return redirect()->back()->with('t-success', 'Content deleted successfully.');
    }
}
