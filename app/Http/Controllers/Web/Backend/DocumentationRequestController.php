<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\DocumentationRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DocumentationRequestController extends Controller
{
    public function __construct()
    {
        View::share('crud', 'documentation_requests');
    }

    /**
     * Display a listing of the requests.
     */
    public function index()
    {
        $requests = DocumentationRequest::latest()->get();
        return view('backend.layouts.documentation_requests.index', compact('requests'));
    }

    /**
     * Display the specified request.
     */
    public function show(string $id)
    {
        $request = DocumentationRequest::findOrFail($id);
        
        // Mark as reviewed if it's still pending
        if ($request->status === 'pending') {
            $request->update(['status' => 'reviewed']);
        }

        return view('backend.layouts.documentation_requests.show', compact('request'));
    }

    /**
     * Remove the specified request.
     */
    public function destroy(string $id)
    {
        try {
            $request = DocumentationRequest::findOrFail($id);
            $request->delete();
            return back()->with('t-success', 'Deleted successfully');
        } catch (Exception $e) {
            return back()->with('t-error', 'Failed to delete: ' . $e->getMessage());
        }
    }
}
