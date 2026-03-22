<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Mail\AdminJobMail;
use App\Mail\UserConfirmMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class JobApplicationController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'country' => 'required|string',
            'phone_number' => 'required|string',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'most_recent_employer' => 'required|string',
            'most_recent_job_title' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->except(['resume', 'cover_letter']);
            $folder = 'uploads/job_applications';

            if (!file_exists(public_path($folder))) {
                mkdir(public_path($folder), 0777, true);
            }

            // File Uploads
            if ($request->hasFile('resume')) {
                $file = $request->file('resume');
                $name = 'resume_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path($folder), $name);
                $data['resume_path'] = $folder . '/' . $name;
            }

            if ($request->hasFile('cover_letter')) {
                $file = $request->file('cover_letter');
                $name = 'cl_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path($folder), $name);
                $data['cover_letter_path'] = $folder . '/' . $name;
            }

            // Database Save
            JobApplication::create($data);

            // Emails Sending
            Mail::to('rayhan259606@gmail.com')->send(new AdminJobMail($data)); // Admin
            Mail::to($data['email'])->send(new UserConfirmMail($data)); // Applicant

            return response()->json([
                'status' => true,
                'message' => 'Application submitted successfully! Check your email.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
