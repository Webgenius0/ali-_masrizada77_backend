<?php

namespace App\Http\Controllers\Api;

use App\Models\CMS;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HeddingTrustController extends Controller
{
    public function getTrustContent(Request $request)
    {
        $type = $request->query('type', 'english');

        $cms = CMS::where('slug', 'trust_heading')
                ->where('section', 'sidebar')
                ->where('type', $type)
                ->first();

        if (!$cms) {
            return response()->json([
                'success' => false,
                'message' => "Content for language '$type' was not found."
            ], 404);
        }

        return response()->json([
            'success' => true,
            'language' => $type,
            'data' => [
                'contact_title' => $cms->metadata['contact_title'] ?? '',
                'contact_desc'  => $cms->metadata['contact_desc'] ?? '',
                'contact_email' => $cms->metadata['contact_email'] ?? '',
                'contact_phone' => $cms->metadata['contact_phone'] ?? '',
            ]
        ], 200);
    }
}
