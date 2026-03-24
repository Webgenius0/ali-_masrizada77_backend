<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function storeContact(Request $request)
    {
        // ১. ভ্যালিডেশন
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        // ২. ডাটা সেভ (Contacts Table)
        try {
            $contact = new Contact();
            $contact->name        = $request->name;
            $contact->email       = $request->email;
            $contact->phone       = $request->phone;
            $contact->designation = $request->designation;
            $contact->company     = $request->company;
            $contact->subject     = $request->subject ?? 'Expert Consultation Request'; // Default Subject
            $contact->message     = $request->message;
            $contact->save();

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully!'
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

}
