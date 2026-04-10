<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use Illuminate\Http\Request;

class AnnouncementApiController extends Controller
{
    public function getAnnouncement(Request $request)
    {
        $type = $request->query('type', 'english');
        
        $data = CMS::where('page', PageEnum::ANNOUNCEMENT)
            ->where('section', SectionEnum::ANNOUNCEMENT)
            ->where('type', $type)
            ->where('status', 'active')
            ->first();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'No announcement found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'text' => $data->title,
                'link_text' => $data->btn_text,
                'link_url' => $data->btn_link,
            ]
        ]);
    }
}
