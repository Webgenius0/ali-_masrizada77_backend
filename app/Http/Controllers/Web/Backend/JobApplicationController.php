<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = JobApplication::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function($row) {
                    // First name এবং Last name জোড়া দিয়ে ফুল নেম দেখানো হচ্ছে
                    return trim($row->first_name . ' ' . $row->last_name);
                })
                ->addColumn('position', function($row) {
                    return $row->position ?? 'N/A';
                })
                ->addColumn('date', function($row) {
                    return $row->created_at->format('d M, Y');
                })
                ->addColumn('action', function($row) {
                    $showUrl = route('admin.home.getalljob.show', $row->id);
                    $deleteUrl = route('admin.home.getalljob.destroy', $row->id);

                    $btn = '<div class="btn-group">
                                <a href="' . $showUrl . '" class="btn btn-sm btn-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" onclick="deleteApplication(' . $row->id . ')" class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-' . $row->id . '" action="' . $deleteUrl . '" method="POST" style="display:none;">
                                    ' . csrf_field() . '
                                    ' . method_field('DELETE') . '
                                </form>
                            </div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.layouts.job_applications.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // ডাটা না পাওয়া গেলে ৪-৪ এরর দেখাবে
        $application = JobApplication::findOrFail($id);

        return view('backend.layouts.job_applications.show', compact('application'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $application = JobApplication::findOrFail($id);

        try {
            // রেজুমি ফাইল যদি আগে ভুলবশত থেকে থাকে তবে ডিলিট হবে
            if ($application->resume_path && file_exists(public_path($application->resume_path))) {
                @unlink(public_path($application->resume_path));
            }

            if ($application->cover_letter_path && file_exists(public_path($application->cover_letter_path))) {
                @unlink(public_path($application->cover_letter_path));
            }

            $application->delete();

            return redirect()->back()->with('t-success', 'Application deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('t-error', 'Something went wrong while deleting.');
        }
    }
}
