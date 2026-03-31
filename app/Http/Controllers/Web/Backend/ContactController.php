<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
    public function __construct()
    {
        View::share('crud', 'Contact Messages');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Contact::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('info', function ($data) {
                    return "<b>Name:</b> $data->name <br> <b>Email:</b> $data->email <br> <b>Phone:</b> " . ($data->phone ?? 'N/A');
                })
                ->addColumn('company_info', function ($data) {
                    return "<b>Company:</b> "   . ($data->company     ?? 'N/A') .
                           "<br> <b>Role:</b> " . ($data->designation ?? 'N/A');
                })
                ->addColumn('business_type', function ($data) {
                    return $data->business_type ?? 'N/A';
                })
                ->addColumn('status', function ($data) {
                    $backgroundColor  = $data->status == "active" ? '#4CAF50' : '#ccc';
                    $sliderTranslateX = $data->status == "active" ? '26px' : '2px';
                    $sliderStyles     = "position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background-color: white; border-radius: 50%; transition: transform 0.3s ease; transform: translateX($sliderTranslateX);";

                    $status  = '<div onclick="showStatusChangeAlert(' . $data->id . ')" class="form-check form-switch" style="position: relative; width: 50px; height: 24px; background-color: ' . $backgroundColor . '; border-radius: 12px; transition: background-color 0.3s ease; cursor: pointer;">';
                    $status .= '<span style="' . $sliderStyles . '"></span>';
                    $status .= '</div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-info text-white view-message"
                                    data-name="'          . $data->name                     . '"
                                    data-business-type="' . ($data->business_type ?? 'N/A') . '"
                                    data-subject="'       . $data->subject                  . '"
                                    data-message="'       . $data->message                  . '"
                                    title="View Message">
                                    <i class="fe fe-eye"></i>
                                </button>
                                <button type="button" onclick="deleteContact(' . $data->id . ')" class="btn btn-danger text-white" title="Delete">
                                    <i class="fe fe-trash"></i>
                                </button>
                            </div>';
                })
                ->rawColumns(['info', 'company_info', 'business_type', 'status', 'action'])
                ->make(true);
        }

        return view("backend.layouts.contact");
    }

    public function status(int $id): JsonResponse
    {
        $data = Contact::find($id);
        if (!$data) {
            return response()->json(['status' => 'error', 'message' => 'Item not found.']);
        }
        $data->status = $data->status === 'active' ? 'inactive' : 'active';
        $data->save();
        return response()->json(['status' => 'success', 'message' => 'Status updated successfully!']);
    }

    public function destroy(int $id): JsonResponse
    {
        $data = Contact::find($id);
        if ($data) {
            $data->delete();
            return response()->json(['status' => 'success', 'message' => 'Message deleted successfully!']);
        }
        return response()->json(['status' => 'error', 'message' => 'Failed to delete.']);
    }
}
