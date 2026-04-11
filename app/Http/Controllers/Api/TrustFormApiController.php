<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use Illuminate\Http\Request;

class TrustFormApiController extends Controller
{
    public function getOptions(Request $request)
    {
        try {
            $type = $request->get('type', 'english');

            $data = CMS::where('slug', 'trust_form_options')
                       ->where('type', $type)
                       ->where('status', 'active')
                       ->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'No options found',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'success' => true,
                'language' => $type,
                'data' => $data->metadata['options'] ?? []
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
