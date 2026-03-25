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
            // শুধুমাত্র প্রয়োজনীয় কলামগুলো সিলেক্ট করা হয়েছে
            $data = Post::latest()->select(['id', 'title', 'team', 'location', 'status', 'type', 'thumbnail', 'created_at']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('thumbnail', function ($post) {
                    $img = ($post->thumbnail && file_exists(public_path($post->thumbnail)))
                        ? asset($post->thumbnail)
                        : asset('default/logo.png'); // default ইমেজ পাথ চেক করে নিন
                    return '<img src="' . $img . '" width="60" class="rounded border shadow-sm">';
                })
                ->addColumn('status', function ($post) {
                    $class = $post->status === 'active' ? 'btn-success' : 'btn-danger';
                    return '<button class="status-btn btn btn-sm ' . $class . '" data-id="' . $post->id . '">'
                           . ucfirst($post->status) . '</button>';
                })
                ->addColumn('type', function ($post) {
                    $badge = $post->type === 'en' ? 'bg-primary' : ($post->type === 'de' ? 'bg-warning' : 'bg-info');
                    return '<span class="badge ' . $badge . '">' . strtoupper($post->type) . '</span>';
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
                ->rawColumns(['thumbnail', 'status', 'type', 'action'])
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
            'team'          => 'nullable|string|max:255',
            'location'      => 'nullable|string|max:255',
            'content'       => 'required|string',
            'thumbnail'     => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'picture'       => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'linkedin_link' => 'nullable|url|max:255',
            'status'        => 'nullable|in:active,inactive',
            'type'          => 'nullable|in:en,de,others',
        ]);

        try {
            $post = new Post();
            $post->title         = $request->title;
            $post->slug          = Str::slug($request->title);
            $post->team          = $request->team;
            $post->location      = $request->location;
            $post->content       = $request->content;
            $post->linkedin_link = $request->linkedin_link;
            $post->status        = $request->status ?? 'active';
            $post->type          = $request->type ?? 'en';

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
            'title'         => 'required|string|max:255',
            'content'       => 'required|string',
            'thumbnail'     => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'picture'       => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        try {
            $post = Post::findOrFail($id);
            $post->title         = $request->title;
            $post->slug          = Str::slug($request->title);
            $post->team          = $request->team;
            $post->location      = $request->location;
            $post->content       = $request->content;
            $post->linkedin_link = $request->linkedin_link;
            $post->status        = $request->status ?? $post->status;
            $post->type          = $request->type ?? $post->type;

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

      session()->put('t-success', 'Post Deleted successfully');
    }

    public function status($id)
    {
        $post = Post::findOrFail($id);
        $post->status = $post->status === 'active' ? 'inactive' : 'active';
        $post->save();

         session()->put('t-success', 'Post status change successfully');
    }
}
