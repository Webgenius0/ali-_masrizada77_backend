<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JobApplicationController extends Controller
{
public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = JobApplication::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function($row){
                    return $row->first_name . ' ' . $row->last_name;
                })
                ->addColumn('resume', function($row){
                    $url = asset($row->resume_path);
                    return '<a href="'.$url.'" target="_blank" class="btn btn-sm btn-info text-white">
                                <i class="fas fa-file-pdf"></i> View CV
                            </a>';
                })
                ->addColumn('action', function($row){
                    $showUrl = route('admin.home.getalljob.show', $row->id);
                    $deleteUrl = route('admin.home.getalljob.destroy', $row->id);

                    $btn = '<div class="btn-group">
                                <a href="'.$showUrl.'" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" onclick="deleteApplication('.$row->id.')" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-'.$row->id.'" action="'.$deleteUrl.'" method="POST" style="display:none;">
                                    '.csrf_field().' '.method_field('DELETE').'
                                </form>
                            </div>';
                    return $btn;
                })
                ->rawColumns(['resume', 'action'])
                ->make(true);
        }

        return view('backend.layouts.job_applications.index');
    }


    public function show($id)
    {
        $application = JobApplication::findOrFail($id);
        return view('backend.layouts.job_applications.show', compact('application'));
    }


    public function destroy($id)
    {
        $application = JobApplication::findOrFail($id);

        if (file_exists(public_path($application->resume_path))) {
            @unlink(public_path($application->resume_path));
        }
        if ($application->cover_letter_path && file_exists(public_path($application->cover_letter_path))) {
            @unlink(public_path($application->cover_letter_path));
        }

        $application->delete();
        return redirect()->back()->with('t-success', 'Application deleted successfully.');
    }
}
