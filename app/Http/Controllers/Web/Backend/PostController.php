<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // ডাটাবেস থেকে সব ডাটা নেয়া হচ্ছে
            $data = Post::latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('thumbnail', function ($post) {
                    $img = ($post->thumbnail && file_exists(public_path($post->thumbnail)))
                        ? asset($post->thumbnail)
                        : asset('default/logo.png');
                    return '<img src="' . $img . '" width="60" class="rounded border shadow-sm">';
                })
                ->addColumn('title_combined', function ($post) {
                    // ডাটাসোর্স থেকে ইংলিশ এবং জার্মান দুই টাইটেলই টেবিলে দেখা যাবে
                    $en = '<strong>EN:</strong> ' . $post->title;
                    $de = '<br><span class="text-info"><strong>DE:</strong> ' . ($post->title_de ?? 'N/A') . '</span>';
                    return $en . $de;
                })
                ->addColumn('status', function ($post) {
                    $class = $post->status === 'active' ? 'btn-success' : 'btn-danger';
                    return '<button class="status-btn btn btn-sm ' . $class . '" data-id="' . $post->id . '">'
                           . ucfirst($post->status) . '</button>';
                })
                ->addColumn('created_at', function ($post) {
                    return $post->created_at ? $post->created_at->format('d M, Y') : 'N/A';
                })
                ->addColumn('action', function ($post) {
                    return '
                        <div class="btn-list">
                            <a href="'.route('admin.post.show', $post->id).'" class="btn btn-sm btn-primary-light" title="View"><i class="fe fe-eye"></i></a>
                            <a href="'.route('admin.post.edit', $post->id).'" class="btn btn-sm btn-warning-light" title="Edit"><i class="fe fe-edit"></i></a>
                            <button class="btn btn-sm btn-danger-light delete-btn" data-id="'.$post->id.'" title="Delete"><i class="fe fe-trash-2"></i></button>
                        </div>';
                })
                ->rawColumns(['thumbnail', 'title_combined', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.post.index');
    }

    public function create()
    {
        return view('backend.layouts.post.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'title_de'      => 'nullable|string|max:255',
            'content'       => 'required|string',
            'content_de'    => 'nullable|string',
            'team'          => 'nullable|string|max:255',
            'location'      => 'nullable|string|max:255',
            'thumbnail'     => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'picture'       => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'linkedin_link' => 'nullable|url|max:255',
            'status'        => 'nullable|in:active,inactive',
        ]);

        try {
            $post = new Post();
            $post->title         = $request->title;
            $post->title_de      = $request->title_de; // জার্মান টাইটেল
            $post->slug          = Str::slug($request->title);
            $post->content       = $request->content;
            $post->content_de    = $request->content_de; // জার্মান কন্টেন্ট
            $post->team          = $request->team;
            $post->location      = $request->location;
            $post->linkedin_link = $request->linkedin_link;
            $post->status        = $request->status ?? 'active';

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = time() . '_thumb.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/posts'), $filename);
                $post->thumbnail = 'uploads/posts/' . $filename;
            }

            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $filename = time() . '_pic.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/posts'), $filename);
                $post->picture = 'uploads/posts/' . $filename;
            }

            $post->save();

            return redirect()->route('admin.post.index')->with('t-success', 'Post created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('backend.layouts.post.show', compact('post'));
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('backend.layouts.post.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'thumbnail'  => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'picture'    => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        try {
            $post = Post::findOrFail($id);
            $post->title         = $request->title;
            $post->title_de      = $request->title_de; // আপডেট জার্মান টাইটেল
            $post->slug          = Str::slug($request->title);
            $post->content       = $request->content;
            $post->content_de    = $request->content_de; // আপডেট জার্মান কন্টেন্ট
            $post->team          = $request->team;
            $post->location      = $request->location;
            $post->linkedin_link = $request->linkedin_link;
            $post->status        = $request->status ?? $post->status;

            if ($request->hasFile('thumbnail')) {
                if ($post->thumbnail && file_exists(public_path($post->thumbnail))) {
                    unlink(public_path($post->thumbnail));
                }
                $file = $request->file('thumbnail');
                $filename = time() . '_thumb.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/posts'), $filename);
                $post->thumbnail = 'uploads/posts/' . $filename;
            }

            if ($request->hasFile('picture')) {
                if ($post->picture && file_exists(public_path($post->picture))) {
                    unlink(public_path($post->picture));
                }
                $file = $request->file('picture');
                $filename = time() . '_pic.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/posts'), $filename);
                $post->picture = 'uploads/posts/' . $filename;
            }

            $post->save();
            return redirect()->route('admin.post.index')->with('t-success', 'Post updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if ($post->thumbnail && file_exists(public_path($post->thumbnail))) {
            unlink(public_path($post->thumbnail));
        }
        if ($post->picture && file_exists(public_path($post->picture))) {
            unlink(public_path($post->picture));
        }
        $post->delete();

        return response()->json(['status' => 'success', 'message' => 'Post Deleted successfully']);
    }

    public function status($id)
    {
        $post = Post::findOrFail($id);
        $post->status = $post->status === 'active' ? 'inactive' : 'active';
        $post->save();

        return response()->json(['status' => 'success', 'message' => 'Post status changed successfully']);
    }
}
