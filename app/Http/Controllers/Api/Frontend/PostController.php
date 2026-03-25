<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * টাইপ অনুযায়ী সব পোস্টের লিস্ট
     * URL Example: /api/posts?type=en
     */
    public function post(Request $request)
    {
        $query = Post::where('status', 'active')->where('type','en');

        // যদি URL-এ type থাকে তবে ফিল্টার করবে
        if ($request->has('type') && $request->type != null) {
            $query->where('type', $request->type);
        }

        $posts = $query->select('id', 'title', 'team', 'location', 'thumbnail', 'type', 'created_at')
            ->latest()
            ->get()
            ->map(function ($post) {
                $post->thumbnail = $post->thumbnail ? asset($post->thumbnail) : null;
                return $post;
            });

        return response()->json([
            'success' => true,
            'message' => $request->type
                         ? strtoupper($request->type) . ' posts retrieved successfully'
                         : 'All active posts retrieved successfully',
            'data'    => $posts
        ], 200);
    }

    /**
     * আইডি এবং টাইপ অনুযায়ী একটি নির্দিষ্ট পোস্টের ফুল ডিটেইলস
     * URL Example: /api/post/5?type=en
     */
    public function show(Request $request, $id)
    {
        // আইডি দিয়ে পোস্টটি খোঁজা হচ্ছে
        $query = Post::where('id', $id)->where('status', 'active');

        // যদি ইউজার নির্দিষ্ট টাইপের পোস্ট দেখতে চায় (যেমন শুধু 'en' পোস্টের ডিটেইলস)
        if ($request->has('type') && $request->type != null) {
            $query->where('type', $request->type);
        }

        $post = $query->first();

        // যদি পোস্ট না পাওয়া যায় বা টাইপ না মিলে
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found or type mismatch'
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
