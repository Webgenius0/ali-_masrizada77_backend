<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use App\Models\CMS;

class LegalCMSApiController extends Controller
{
    /**
     * Privacy Policy Data niye ashar jonno
     */
    public function getPrivacyPolicy()
    {
        try {
            $type = request('type', 'english');
            $data = CMS::where('page', 'legal')
                       ->where('section', 'privacy_policy')
                       ->where('type', $type)
                       ->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Privacy Policy not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => [
                    'description' => $data->description,
                    'updated_at'  => $data->updated_at->format('Y-m-d H:i:s'),
                ]
            ], 200);

        }
         catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Terms & Conditions Data niye ashar jonno
     */
    public function getTermsConditions()
    {
        try {
            $type = request('type', 'english');
            $data = CMS::where('page', 'legal')
                       ->where('section', 'terms_conditions')
                       ->where('type', $type)
                       ->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terms & Conditions not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => [
                    'description' => $data->description,
                    'updated_at'  => $data->updated_at->format('Y-m-d H:i:s'),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}