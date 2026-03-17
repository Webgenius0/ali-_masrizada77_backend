<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Exception;

class BlogController extends Controller
{
    /**
     * Type অনুযায়ী ব্লগের লিস্ট নিয়ে আসা
     */
public function index(Request $request)
{
    $query = Blog::where('status', 'active');

    if ($request->filled('type')) {
        $query->where('type', $request->type);
    } else {
        $query->where('type', 'english');
    }

    $blogs = $query->latest()->get();

    $formattedBlogs = $blogs->map(function ($blog) {
        return [
            'id'               => $blog->id,
            'type'             => ucfirst($blog->type),
            'title'            => $blog->title,
            'subtitle'         => $blog->subtitle ?? '',
            'image_url'        => $blog->image ? asset($blog->image) : null,
            'description_html' => $blog->description,
            'description_raw'  => strip_tags($blog->description),
            'created_date'     => $blog->created_at->format('d M, Y'),
        ];
    });

    return response()->json([
        'status'  => 'success',
        'message' => $formattedBlogs->isEmpty() ? 'No blogs found' : 'Data retrieved successfully',
        'count'   => $formattedBlogs->count(),
        'data'    => $formattedBlogs
    ], 200);
}

    /**
     * নির্দিষ্ট একটি ব্লগের ডিটেইলস দেখা
     */
    public function show($id)
    {
        try {
            $blog = Blog::find($id);

            if (!$blog) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Blog not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $blog
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
