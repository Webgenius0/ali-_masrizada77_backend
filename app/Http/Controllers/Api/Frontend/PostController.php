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
        $type = $request->query('type', 'en'); // en অথবা de

        $posts = Post::where('status', 'active')
            ->latest()
            ->get()
            ->map(function ($post) use ($type) {
                return [
                    'id'             => $post->id,
                    // টাইপ অনুযায়ী ডাটা রিটার্ন করবে
                    'title'          => $type == 'de' ? ($post->title_de ?? $post->title) : $post->title,
                    'content'        => $type == 'de' ? ($post->content_de ?? $post->content) : $post->content,
                    'team'           => $post->team,
                    'location'       => $post->location,
                    'thumbnail'      => $post->thumbnail ? asset($post->thumbnail) : null,
                    'formatted_date' => $post->created_at->format('d M, Y'),
                ];
            });

        return response()->json([
            'success' => true,
            'message' => strtoupper($type) . ' posts retrieved successfully',
            'data'    => $posts
        ], 200);
    }

    public function post_carrer(Request $request)
    {
        $type = $request->query('type', 'en');

        $posts = Post::where('status', 'active')
            ->latest()
            ->get()
            ->map(function ($post) use ($type) {
                return [
                    'id'             => $post->id,
                    'title'          => $type == 'de' ? ($post->title_de ?? $post->title) : $post->title,
                    'team'           => $post->team,
                    'location'       => $post->location,
                    'formatted_date' => $post->created_at->format('d M, Y'),
                ];
            });

        return response()->json([
            'success' => true,
            'message' => strtoupper($type) . ' posts retrieved successfully',
            'data'    => $posts
        ], 200);
    }

    /**
     * আইডি অনুযায়ী একটি নির্দিষ্ট পোস্টের ডিটেইলস
     */
    public function show(Request $request, $id)
    {
        $type = $request->query('type', 'en');

        $post = Post::where('id', $id)
            ->where('status', 'active')
            ->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Post details retrieved successfully',
            'data'    => [
                'id'            => $post->id,
                // যদি টাইপ 'de' হয় এবং ডাটা থাকে তবে সেটা দিবে, নাহলে ইংলিশটাই দিবে (fallback)
                'title'         => $type == 'de' ? ($post->title_de ?? $post->title) : $post->title,
                'content'       => $type == 'de' ? ($post->content_de ?? $post->content) : $post->content,
                'team'          => $post->team,
                'location'      => $post->location,
                'thumbnail'     => $post->thumbnail ? asset($post->thumbnail) : null,
                'picture'       => $post->picture ? asset($post->picture) : null,
                'linkedin_link' => $post->linkedin_link,
                'created_at'    => $post->created_at->format('d M, Y')
            ]
        ], 200);
    }
}
