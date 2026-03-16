<?php

namespace App\Http\Controllers\Web\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Exception;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class FaqController extends Controller
{
    public function __construct()
    {
        View::share('crud', 'faq');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = FAQ::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('question', function ($data) {
                    return '<span title="' . e($data->question) . '">' .
                        Str::limit($data->question, 20) . ' ?</span>';
                })
                ->addColumn('answer', function ($data) {
                    return Str::limit(strip_tags($data->answer), 40);
                })

                ->addColumn('status', function ($data) {
                    $backgroundColor = $data->status == "active" ? '#4CAF50' : '#ccc';
                    $sliderTranslateX = $data->status == "active" ? '26px' : '2px';

                    return '
                        <div class="form-check form-switch" style="margin-left:40px; position: relative; width: 50px; height: 24px; background-color: ' . $backgroundColor . '; border-radius: 12px; cursor:pointer;">
                            <input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox"
                                class="form-check-input"
                                style="position:absolute;width:100%;height:100%;opacity:0;z-index:2;">
                            <span style="position:absolute;top:2px;left:2px;width:20px;height:20px;background:#fff;border-radius:50%;transform:translateX(' . $sliderTranslateX . ');transition:.3s"></span>
                        </div>';
                })
                ->addColumn('action', function ($data) {
                    return '
                        <div class="btn-group btn-group-sm">
                            <a href="#" onclick="goToEdit(' . $data->id . ')" class="btn btn-primary">
                                <i class="fe fe-edit"></i>
                            </a>
                            <a href="#" onclick="goToOpen(' . $data->id . ')" class="btn btn-success">
                                <i class="fe fe-eye"></i>
                            </a>
                            <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-danger">
                                <i class="fe fe-trash"></i>
                            </a>
                        </div>';
                })
                ->rawColumns(['question', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.faq.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.layouts.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // ১. ভ্যালিডেশন
    $validator = Validator::make($request->all(), [
        'question' => 'required|string',
        'answer'   => 'required|string',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        // ২. 'new' ছাড়াই ডেটা সেভ করার পদ্ধতি (Static Create Method)
        FAQ::create([
            'question' => $request->question,
            'answer'   => $request->answer,
            'status'   => 'active',
        ]);

        session()->put('t-success', 'FAQ created successfully');
    } catch (Exception $e) {
        session()->put('t-error', $e->getMessage());
        return redirect()->back();
    }

    return redirect()->route('admin.faq.index');
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $faq = FAQ::findOrFail($id);
        return view('backend.layouts.faq.show', compact('faq'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $faq = FAQ::findOrFail($id);
        return view('backend.layouts.faq.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'question' => 'required|string',
        'answer'   => 'required|string',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        $faq = FAQ::findOrFail($id);
        // শুধুমাত্র প্রশ্ন এবং উত্তর আপডেট হবে
        $faq->question = $request->question;
        $faq->answer   = $request->answer;
        $faq->save();

        session()->put('t-success', 'FAQ updated successfully');
    } catch (Exception $e) {
        session()->put('t-error', $e->getMessage());
    }

    return redirect()->route('admin.faq.index');
}


//img start



//img end
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            FAQ::findOrFail($id)->delete();
            return response()->json([
                'status' => 't-success',
                'message' => 'Deleted successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 't-error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Change status
     */
    public function status(int $id): JsonResponse
    {
        $data = FAQ::findOrFail($id);
        $data->status = $data->status === 'active' ? 'inactive' : 'active';
        $data->save();

        return response()->json([
            'status' => 't-success',
            'message' => 'Status updated successfully',
        ]);
    }

}
