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
        try {
            // শুধুমাত্র একটিভ ব্লগগুলো নিব
            $blogs = Blog::where('status', 'active')->latest()->get();

            // ফ্রন্টএন্ড থেকে আসা ল্যাঙ্গুয়েজ টাইপ (Default: english)
            $lang = $request->get('type', 'english');

            $formattedBlogs = $blogs->map(function ($blog) use ($lang) {
                // চেক করছি টাইপ কি জার্মান (de) কিনা
                $isDe = ($lang == 'de' || $lang == 'german');

                return [
                    'id'               => $blog->id,
                    'type'             => ucfirst($lang),
                    // টাইপ অনুযায়ী টাইটেল, সাবটাইটেল এবং ডেসক্রিপশন সেট করা হচ্ছে
                    'title'            => $isDe ? ($blog->title_de ?? $blog->title) : $blog->title,
                    'subtitle'         => $isDe ? ($blog->subtitle_de ?? $blog->subtitle) : $blog->subtitle,
                    'image_url'        => $blog->image ? asset($blog->image) : null,
                    'description_html' => $isDe ? ($blog->description_de ?? $blog->description) : $blog->description,
                    'description_raw'  => strip_tags($isDe ? ($blog->description_de ?? $blog->description) : $blog->description),
                    'created_date'     => $blog->created_at->format('d M, Y'),
                ];
            });

            return response()->json([
                'status'  => 'success',
                'message' => $formattedBlogs->isEmpty() ? 'No blogs found' : 'Data retrieved successfully',
                'count'   => $formattedBlogs->count(),
                'data'    => $formattedBlogs
            ], 200);

        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function suggestions(Request $request)
    {
        try {
            $lang = $request->get('type', 'english');
            $isDe = ($lang == 'de' || $lang == 'german');

            $suggestedBlogs = Blog::where('status', 'active')->latest()->take(4)->get();

            $formattedSuggestions = $suggestedBlogs->map(function ($item) use ($isDe) {
                return [
                    'id'               => $item->id,
                    'title'            => $isDe ? ($item->title_de ?? $item->title) : $item->title,
                    'subtitle'         => $isDe ? ($item->subtitle_de ?? $item->subtitle) : $item->subtitle,
                    'image_url'        => $item->image ? asset($item->image) : null,
                    'description_raw'  => strip_tags($isDe ? ($item->description_de ?? $item->description) : $item->description),
                    'created_date'     => $item->created_at->format('d M, Y'),
                ];
            });

            return response()->json([
                'status'  => 'success',
                'count'   => $formattedSuggestions->count(),
                'data'    => $formattedSuggestions
            ], 200);

        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $blog = Blog::find($id);

            if (!$blog) {
                return response()->json(['status' => 'error', 'message' => 'Blog not found'], 404);
            }

            $lang = $request->get('type', 'english');
            $isDe = ($lang == 'de' || $lang == 'german');

            $data = [
                'id'               => $blog->id,
                'title'            => $isDe ? ($blog->title_de ?? $blog->title) : $blog->title,
                'subtitle'         => $isDe ? ($blog->subtitle_de ?? $blog->subtitle) : $blog->subtitle,
                'image_url'        => $blog->image ? asset($blog->image) : null,
                'description_html' => $isDe ? ($blog->description_de ?? $blog->description) : $blog->description,
                'created_at'       => $blog->created_at->format('d M, Y'),
            ];

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);

        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
