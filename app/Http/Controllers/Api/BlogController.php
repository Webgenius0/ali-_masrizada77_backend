<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Exception;

class BlogController extends Controller
{
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

public function suggestions()
{
    try {


        // suggation
        $suggestedBlogs = Blog::where('status', 'active')    // rome this blog is visible
            ->latest()
            ->take(4)                       // 4 blog
            ->get();


        $formattedSuggestions = $suggestedBlogs->map(function ($item) {
            return [
                'id'               => $item->id,
                'type'             => ucfirst($item->type),
                'title'            => $item->title,
                'subtitle'         => $item->subtitle ?? '',
                'image_url'        => $item->image ? asset($item->image) : null,
                'description_raw'  => strip_tags($item->description),
                'created_date'     => $item->created_at->format('d M, Y'),
            ];
        });

        return response()->json([
            'status'  => 'success',
            'count'   => $formattedSuggestions->count(),
            'data'    => $formattedSuggestions
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'status'  => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}



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
