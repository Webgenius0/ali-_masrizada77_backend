<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ElevenLabsController extends Controller
{
    public function getSignedUrl()
    {
        $agentId = "agent_01jw8gvfrvfr680qewj41n2y98";
        $apiKey  = env('ELEVENLABS_API_KEY'); // .env থেকে নাও, hardcode করো না production-এ

        try {
            $response = Http::withHeaders([
                'xi-api-key' => $apiKey,
            ])->withOptions([
                'verify' => false, // শুধু local/dev-এ রাখো, production-এ true করো বা SSL fix করো
            ])->get("https://api.elevenlabs.io/v1/convai/conversation/get-signed-url", [
                'agent_id' => $agentId,
                // optional: 'include_conversation_id' => true, যদি conversation_id লাগে
            ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'error'   => 'ElevenLabs API Error',
                    'status'  => $response->status(),
                    'message' => $response->json()['detail'] ?? $response->body(),
                    'full_response' => $response->json()
                ], $response->status());
            }

            // সফল হলে signed_url return করো
            return response()->json([
                'success'    => true,
                'signed_url' => $response->json()['signed_url'] ?? null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Server Error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function testVoice()
    {
        return view('backend.layouts.cms.voice_test');
    }
}
