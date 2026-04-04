<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Mail\AdminDocumentationRequestMail;
use App\Mail\ClientDocumentationRequestMail;
use App\Models\DocumentationRequest;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DocumentationRequestController extends Controller
{
    /**
     * Store a new documentation request.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name'     => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'company_name'   => 'required|string|max:255',
            'document_type'  => 'required|string|max:255',
            'message'        => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return Helper::jsonResponse(false, 'Validation Error', 422, ['errors' => $validator->errors()]);
        }

        try {
            $docRequest = DocumentationRequest::create($validator->validated());

            // Get admin email from settings
            $setting = Setting::first();
            // $adminEmail = $setting->email ?? 'admin@admin.com';

            // Send notification email to admin
            Mail::to(config('mail.from.address'))->send(new AdminDocumentationRequestMail($docRequest));

            // Send acknowledgment email to client
            Mail::to($docRequest->email)->send(new ClientDocumentationRequestMail($docRequest));

            return Helper::jsonResponse(true, 'Your request has been submitted successfully.', 201, $docRequest);

        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to submit request: ' . $e->getMessage(), 500);
        }
    }
}
