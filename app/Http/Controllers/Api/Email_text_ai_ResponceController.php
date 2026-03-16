<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use Illuminate\Http\Request;
use Exception;

class Email_text_ai_ResponceController extends Controller
{


    public function email_text_ai_responce(Request $request)
    {
        try {
            $type = $request->query('type', 'english');

            // আপনার Web Controller এর সাথে মিল রেখে ফিল্টার করা হলো
            $data = CMS::where('page', PageEnum::EmailAndTextAI) // অথবা PageEnum::ConversationalAI->value
                ->where('section', SectionEnum::INTRO)              // অথবা SectionEnum::INTRO->value
                ->where('type', $type)
                ->where('status', 'active')
                ->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'No content found for this type.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'hero_section' => [
                        'title'       => $data->metadata['sec1_title'] ?? '',
                        'description' => $data->metadata['sec1_desc'] ?? '',
                        'button_text' => $data->metadata['sec1_url_title'] ?? '',
                        'button_url'  => $data->metadata['sec1_url_link'] ?? '',
                        'video'       => $data->video ? asset($data->video) : null,
                    ],
                    'convert_conversition' => [
                        'main_title'       => $data->metadata['sec2_right_title'] ?? '',
                        'main_description' => $data->metadata['sec2_right_desc'] ?? '',
                        'items'            => $data->metadata['sec2_items'] ?? [],
                    ],
                    'automatie_customer_response' => [
                        'title'       => $data->metadata['sec3_title'] ?? '',
                        'description' => $data->metadata['sec3_desc'] ?? '',
                        'image'       => $data->image4 ? asset($data->image4) : null,
                    ],
                    'more_than_just_a_messgae' => [
                        'title'    => $data->metadata['sec4_title'] ?? '',
                        'subtitle' => $data->metadata['sec4_subtitle'] ?? '',
                        'features' => $data->metadata['sec4_features'] ?? [],
                        'video'    => isset($data->metadata['sec4_image']) ? asset($data->metadata['sec4_image']) : null,
                    ],
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
    }
}
