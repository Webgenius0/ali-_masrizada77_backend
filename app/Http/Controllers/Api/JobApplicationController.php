<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\CMS;
use App\Mail\AdminJobMail;
use App\Mail\UserConfirmMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Exception;

class JobApplicationController extends Controller
{
    public function getPositions(Request $request)
    {
        try {
            $type = $request->query('type', 'english');
            $data = CMS::where('slug', 'job_position_options')
                       ->where('type', $type)
                       ->first();

            $options = $data->metadata['options'] ?? [];

            return response()->json([
                'success' => true,
                'data'    => $options
            ], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name'    => 'required|string|max:100',
            'email'        => 'required|email|max:100',
            'country'      => 'required|string',
            'phone_number' => 'required|string|max:20',
            'position'     => 'required|string',
            'why_novavoca' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $nameParts = explode(' ', trim($request->full_name), 2);
            $firstName = $nameParts[0];
            $lastName  = isset($nameParts[1]) ? $nameParts[1] : ' ';

            $data = [
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'email'      => $request->email,
                'country'    => $request->country,
                'phone_number' => $request->phone_number,
                'position'     => $request->position,

                'most_recent_employer'  => $request->why_novavoca ?? 'N/A',
                'most_recent_job_title' => 'N/A',
                'resume_path'           => 'no_file',
            ];

            JobApplication::create($data);

            Mail::to(config('mail.from.address'))->send(new AdminJobMail($data));
            Mail::to($request->email)->send(new UserConfirmMail($data));

            return response()->json(['status' => true, 'message' => 'Application submitted successfully!'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong.'], 500);
        }
    }
}
