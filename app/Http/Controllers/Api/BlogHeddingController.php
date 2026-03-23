<?php

namespace App\Http\Controllers\Api;

use App\Models\CMS;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogHeddingController extends Controller
{
    public function getBlogContent(Request $request)
    {

        $type = $request->query('type', 'english');

        $cms = CMS::where('page', 'blog')
                ->where('section', 'blog_heading')
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
                'title'       => $cms->title ?? '',
                'description' => $cms->description ?? '',
                'image'       => $cms->image1 ? asset($cms->image1) : null,
                'status'      => $cms->status,
            ]
        ], 200);
    }
}
