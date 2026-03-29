<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * টাইপ অনুযায়ী সব পোস্টের লিস্ট
     * Default Type: 'en'
     */
    public function post(Request $request)
    {
        // URL-এ type না থাকলে default 'en' নিবে
        $type = $request->query('type', 'en');

        $posts = Post::where('status', 'active')
            ->where('type', $type)
            ->select('id', 'title', 'team', 'location', 'thumbnail','content', 'type', 'created_at')
            ->latest()
            ->get()
            ->map(function ($post) {
                $post->thumbnail = $post->thumbnail ? asset($post->thumbnail) : null;
                $post->formatted_date = $post->created_at->format('d M, Y');
                return $post;
            });

        return response()->json([
            'success' => true,
            'message' => strtoupper($type) . ' posts retrieved successfully',
            'data'    => $posts
        ], 200);
    }

    public function post_carrer(Request $request)
    {
        // URL-এ type না থাকলে default 'en' নিবে
        $type = $request->query('type', 'en');

        $posts = Post::where('status', 'active')
            ->where('type', $type)
            ->select('id', 'title', 'team', 'location', 'type', 'created_at')
            ->latest()
            ->get()
            ->map(function ($post) {
                $post->formatted_date = $post->created_at->format('d M, Y');
                return $post;
            });

        return response()->json([
            'success' => true,
            'message' => strtoupper($type) . ' posts retrieved successfully',
            'data'    => $posts
        ], 200);
    }

    /**
     * আইডি অনুযায়ী একটি নির্দিষ্ট পোস্টের ডিটেইলস
     * Default Type: 'en'
     */
    public function show(Request $request, $id)
    {
        $type = $request->query('type', 'en');

        $post = Post::where('id', $id)
            ->where('status', 'active')
            ->where('type', $type)
            ->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found for type: ' . $type
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Post details retrieved successfully',
            'data'    => [
                'id'            => $post->id,
                'title'         => $post->title,
                'slug'          => $post->slug,
                'team'          => $post->team,
                'location'      => $post->location,
                'content'       => $post->content,
                'thumbnail'     => $post->thumbnail ? asset($post->thumbnail) : null,
                'picture'       => $post->picture ? asset($post->picture) : null,
                'linkedin_link' => $post->linkedin_link,
                'type'          => $post->type,
                'status'        => $post->status,
                'created_at'    => $post->created_at->format('d M, Y')
            ]
        ], 200);
    }
}
