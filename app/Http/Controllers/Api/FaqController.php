<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Faq;


class FaqController extends Controller
{

    public function index(Request $request)
    {
        try {
            // কুয়েরি শুরু করা
            $query = Faq::where('status', 1); // শুধু একটিভ FAQ গুলো দেখাবে

            // যদি রিকোয়েস্টে 'type' থাকে (যেমন: ?type=english)
            if ($request->has('type') && $request->type != 'all') {
                $query->where('type', $request->type);
            }

            // লেটেস্ট গুলো আগে দেখাবে
            $faqs = $query->latest()->get();

            return response()->json([
                'success' => true,
                'message' => 'FAQ data retrieved successfully.',
                'data'    => $faqs
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
