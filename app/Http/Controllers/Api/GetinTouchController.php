<?php

namespace App\Http\Controllers\Api;
use App\Models\CMS;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetinTouchController extends Controller
{
   public function getContactSidebar(Request $request)
    {

        $type = $request->query('type', 'english');


        $cms = CMS::where('slug', 'get-in-touch')
                  ->where('type', $type)
                  ->first();

        if (!$cms) {
            return response()->json([
                'success' => false,
                'message' => "Content for '$type' was not found."
            ], 404);
        }

        $meta = $cms->metadata;

        return response()->json([
            'success' => true,
            'language' => $type,
            'data' => [
                'title'       => $meta['contact_title'] ?? '',
                'description' => $meta['contact_desc'] ?? '',

                'email' => $meta['contact_email'] ?? '',
                'phone' => $meta['contact_phone'] ?? '',

            ]
        ], 200);
    }
}
