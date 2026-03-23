<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class Hedding_BlogController extends Controller
{
    public function index(Request $request) {
        $type = $request->type ?? 'english';

        // ব্লেড ফাইলের হ্যালু অনুযায়ী 'heading' সেকশন চেক করা হচ্ছে
        $data = CMS::where('page', 'blog')
                   ->where('section', 'heading')
                   ->where('type', $type)
                   ->first();

        return view('backend.layouts.blog_heading.index', compact('data'));
    }

    public function store(Request $request) {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'image1'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'page'        => 'required',
            'section'     => 'required',
            'type'        => 'required',
        ]);

        // ডাটা খুঁজে বের করা অথবা নতুন তৈরি করা
        $data = CMS::where('page', $request->page)
                   ->where('section', $request->section)
                   ->where('type', $request->type)
                   ->first() ?: new CMS();

        $data->page        = $request->page;
        $data->section     = $request->section;
        $data->type        = $request->type;
        $data->title       = $request->title;
        $data->description = $request->description;

        if ($request->hasFile('image1')) {
            // পুরনো ফাইল থাকলে ডিলিট করা
            if ($data->image1 && File::exists(public_path($data->image1))) {
                File::delete(public_path($data->image1));
            }

            $file = $request->file('image1');
            $filename = time() . '_' . $request->type . '.' . $file->getClientOriginalExtension();
            $directory = 'uploads/cms/blog_heading/';

            // ডিরেক্টরি না থাকলে তৈরি করা
            if (!File::isDirectory(public_path($directory))) {
                File::makeDirectory(public_path($directory), 0777, true, true);
            }

            $file->move(public_path($directory), $filename);
            $data->image1 = $directory . $filename;
        }

        $data->save();

        return redirect()->back()->with('t-success', strtoupper($request->type) . ' content updated successfully!');
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
