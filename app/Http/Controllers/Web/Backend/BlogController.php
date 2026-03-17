<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    public $part;
    public $route;
    public $view;

    public function __construct()
    {
        $this->part = 'blog';
        $this->route = 'admin.' . $this->part;
        $this->view = 'backend.layouts.' . $this->part;
        View::share('crud', 'blog');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Blog::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($data) {
                    return '<strong>' . Str::limit($data->title, 20) . '</strong>';
                })
                ->addColumn('type', function ($data) {
                    // ল্যাঙ্গুয়েজ টাইপ অনুযায়ী কালারফুল ব্যাজ
                    $badge = [
                        'english' => 'bg-info',
                        'de'      => 'bg-warning',
                        'other'   => 'bg-secondary'
                    ];
                    $class = $badge[$data->type] ?? 'bg-primary';
                    return '<span class="badge ' . $class . ' text-dark">' . ucfirst($data->type) . '</span>';
                })
                ->addColumn('image', function ($data) {
                    $url = ($data->image && file_exists(public_path($data->image)))
                        ? asset($data->image)
                        : asset('backend/images/default.png');

                    return '<img src="' . $url . '" alt="Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">';
                })
                ->addColumn('status', function ($data) {
                    $backgroundColor = $data->status == "active" ? '#4CAF50' : '#ccc';
                    $sliderTranslateX = $data->status == "active" ? '26px' : '2px';

                    $status = '<div class="d-flex justify-content-center align-items-center">';
                    $status .= '<div class="form-check form-switch" style="position: relative; width: 50px; height: 24px; background-color: ' . $backgroundColor . '; border-radius: 12px; transition: background-color 0.3s ease; cursor: pointer;">';
                    $status .= '<input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" name="status" style="position: absolute; width: 100%; height: 100%; opacity: 0; z-index: 2; cursor: pointer;">';
                    $status .= '<span style="position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background-color: white; border-radius: 50%; transition: transform 0.3s ease; transform: translateX(' . $sliderTranslateX . ');"></span>';
                    $status .= '</div></div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group">
                                <a href="' . route($this->route . '.edit', $data->id) . '" class="btn btn-primary fs-14 text-white" title="Edit"><i class="fe fe-edit"></i></a>
                                <a href="#" onclick="goToOpen(' . $data->id . ')" class="btn btn-success fs-14 text-white" title="View"><i class="fe fe-eye"></i></a>
                                <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-danger fs-14 text-white" title="Delete"><i class="fe fe-trash"></i></a>
                            </div>';
                })
                ->rawColumns(['title', 'type', 'image', 'status', 'action'])
                ->make(true);
        }

        return view($this->view . ".index", [
            'part' => $this->part,
            'route' => $this->route
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view($this->view . ".create", [
            'part' => $this->part,
            'route' => $this->route
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|max:250',
            'subtitle'    => 'nullable|max:250',
            'type'        => 'required|in:english,de,other', // টাইপ ভ্যালিডেশন
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $blog = new Blog();
            $blog->title       = $request->title;
            $blog->subtitle    = $request->subtitle;
            $blog->type        = $request->type;
            $blog->description = $request->description;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/blogs'), $imageName);
                $blog->image = 'uploads/blogs/' . $imageName;
            }

            $blog->save();
            return redirect()->route($this->route . '.index')->with('t-success', 'Blog Created Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view($this->view . ".edit", [
            'part'  => $this->part,
            'route' => $this->route,
            'blog'  => $blog
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|max:250',
            'subtitle'    => 'nullable|max:250',
            'type'        => 'required|in:english,de,other',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $blog = Blog::findOrFail($id);
            $blog->title       = $request->title;
            $blog->subtitle    = $request->subtitle;
            $blog->type        = $request->type;
            $blog->description = $request->description;

            if ($request->hasFile('image')) {
                // পুরাতন ফাইল ডিলিট করা
                if ($blog->image && File::exists(public_path($blog->image))) {
                    File::delete(public_path($blog->image));
                }

                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/blogs'), $imageName);
                $blog->image = 'uploads/blogs/' . $imageName;
            }

            $blog->save();
            return redirect()->route($this->route . '.index')->with('t-success', 'Blog Updated Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $blog = Blog::findOrFail($id);
            if ($blog->image && File::exists(public_path($blog->image))) {
                File::delete(public_path($blog->image));
            }
            $blog->delete();
            return response()->json([
                'status'  => 't-success',
                'message' => 'Deleted successfully!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 't-error',
                'message' => 'Something went wrong!'
            ]);
        }
    }

    /**
     * Toggle status.
     */
    public function status(int $id): JsonResponse
    {
        $data = Blog::findOrFail($id);
        $data->status = $data->status === 'active' ? 'inactive' : 'active';
        $data->save();
        return response()->json([
            'status'  => 't-success',
            'message' => 'Status updated successfully!',
        ]);
    }

    /**
 * Display the specified resource.
 */
public function show($id)
{
    try {
        $blog = Blog::findOrFail($id);
        return view($this->view . ".show", [
            'part' => $this->part,
            'route' => $this->route,
            'blog' => $blog
        ]);
    } catch (Exception $e) {
        return redirect()->back()->with('t-error', 'Blog not found!');
    }
}
}
