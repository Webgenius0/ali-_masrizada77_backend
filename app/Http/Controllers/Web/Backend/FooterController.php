<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Footer;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class FooterController extends Controller
{
    public function __construct()
    {
        View::share('crud', 'footer');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Footer::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($data) {
                    $backgroundColor = $data->status == "active" ? '#4CAF50' : '#ccc';
                    $sliderTranslateX = $data->status == "active" ? '26px' : '2px';
                    $sliderStyles = "position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background-color: white; border-radius: 50%; transition: transform 0.3s ease; transform: translateX($sliderTranslateX);";

                    $status = '<div class="form-check form-switch" style="margin-left:40px; position: relative; width: 50px; height: 24px; background-color: ' . $backgroundColor . '; border-radius: 12px; transition: background-color 0.3s ease; cursor: pointer;">';
                    $status .= '<input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status" style="position: absolute; width: 100%; height: 100%; opacity: 0; z-index: 2; cursor: pointer;">';
                    $status .= '<span style="' . $sliderStyles . '"></span>';
                    $status .= '<label for="customSwitch' . $data->id . '" class="form-check-label" style="margin-left: 10px;"></label>';
                    $status .= '</div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                <a href="' . route('admin.footer.edit', $data->id) . '" class="btn btn-primary fs-14 text-white delete-icn" title="Edit">
                                    <i class="fe fe-edit"></i>
                                </a>
                                <a href="#" type="button" onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-danger fs-14 text-white delete-icn" title="Delete">
                                    <i class="fe fe-trash"></i>
                                </a>
                            </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make();
        }
        return view("backend.layouts.footer.index");
    }

    public function create()
    {
        return view('backend.layouts.footer.create');
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'type'     => 'required|in:english,de,others',
            'category' => 'nullable|string|max:255',
            'title'    => 'required|string|max:255',
            'url'      => 'nullable|string|max:255',
            'order'    => 'nullable|integer',
        ]);

        try {
            Footer::create($validate);
            session()->put('t-success', 'Footer content created successfully');
        } catch (Exception $e) {
            session()->put('t-error', $e->getMessage());
        }

        return redirect()->route('admin.footer.index');
    }

    public function edit($id)
    {
        $footer = Footer::findOrFail($id);
        return view('backend.layouts.footer.edit', compact('footer'));
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'type'     => 'required|in:english,de,others',
            'category' => 'nullable|string|max:255',
            'title'    => 'required|string|max:255',
            'url'      => 'nullable|string|max:255',
            'order'    => 'nullable|integer',
        ]);

        try {
            $footer = Footer::findOrFail($id);
            $footer->update($validate);
            session()->put('t-success', 'Footer content updated successfully');
        } catch (Exception $e) {
            session()->put('t-error', $e->getMessage());
        }

        return redirect()->route('admin.footer.index');
    }

    public function destroy($id)
    {
        try {
            $data = Footer::findOrFail($id);
            $data->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Footer content deleted successfully!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function status($id): JsonResponse
    {
        $data = Footer::findOrFail($id);
        $data->status = $data->status === 'active' ? 'inactive' : 'active';
        $data->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully!',
        ]);
    }
}
