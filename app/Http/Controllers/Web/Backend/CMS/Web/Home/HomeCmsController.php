<?php

namespace App\Http\Controllers\Web\Backend\CMS\Web\Home;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HomeCmsController extends Controller
{
    /**
     * CMS Index Page
     */
    public function index(Request $request, $slug)
    {
        $type = $request->get('type', 'english');

        $data = CMS::where('page', 'home')
                   ->where('slug', $slug)
                   ->where('type', $type)
                   ->first();

        return view('backend.cms.index', compact('data', 'type', 'slug'));
    }

    /**
     * CMS Update/Create logic
     */
    public function update(Request $request, $slug)
    {
        $type = $request->type ?? 'english';

        // বিদ্যমান ডেটা খুঁজে বের করা
        $cms = CMS::where('page', 'home')
                  ->where('slug', $slug)
                  ->where('type', $type)
                  ->first();

        // ১. Hero Video Upload
        $videoPath = $cms->video ?? null;
        if ($request->hasFile('video_file')) {
            if ($videoPath && File::exists(public_path($videoPath))) {
                File::delete(public_path($videoPath));
            }
            $videoPath = 'uploads/cms/' . time() . '_hero_' . $slug . '.' . $request->video_file->extension();
            $request->video_file->move(public_path('uploads/cms/'), $videoPath);
        }

        // ২. Feature Image (Image1) Upload
        $image1 = $cms->image1 ?? null;
        if ($request->hasFile('image1')) {
            if ($image1 && File::exists(public_path($image1))) {
                File::delete(public_path($image1));
            }
            $image1 = 'uploads/cms/' . time() . '_img1_' . $slug . '.' . $request->image1->extension();
            $request->image1->move(public_path('uploads/cms/'), $image1);
        }

        // ৩. Case Study Image (Image2) Upload
        $image2 = $cms->image2 ?? null;
        if ($request->hasFile('image2')) {
            if ($image2 && File::exists(public_path($image2))) {
                File::delete(public_path($image2));
            }
            $image2 = 'uploads/cms/' . time() . '_img2_' . $slug . '.' . $request->image2->extension();
            $request->image2->move(public_path('uploads/cms/'), $image2);
        }

        // ৪. Bottom Section Video (Image4 হিসেবে সেভ হবে)
        $bottomVideo = $cms->image4 ?? null;
        if ($request->hasFile('bottom_video')) {
            if ($bottomVideo && File::exists(public_path($bottomVideo))) {
                File::delete(public_path($bottomVideo));
            }
            $bottomVideo = 'uploads/cms/bottom_' . time() . '_' . $slug . '.' . $request->bottom_video->extension();
            $request->bottom_video->move(public_path('uploads/cms/'), $bottomVideo);
        }

        // ৫. Industry Items Handling
        $industry_items = $request->industry ?? [];
        foreach ($industry_items as $key => $item) {
            $old_ind_img = $cms->metadata['industry_items'][$key]['img'] ?? null;
            if ($request->hasFile("industry.$key.image")) {
                if ($old_ind_img && File::exists(public_path($old_ind_img))) {
                    File::delete(public_path($old_ind_img));
                }
                $imgName = 'uploads/cms/ind_' . time() . "_{$slug}_{$key}." . $request->file("industry.$key.image")->extension();
                $request->file("industry.$key.image")->move(public_path('uploads/cms/'), $imgName);
                $industry_items[$key]['img'] = $imgName;
            } else {
                $industry_items[$key]['img'] = $old_ind_img;
            }
        }

        // ৬. CX Features Handling
        $cx_items = $request->cx_items ?? [];
        foreach ($cx_items as $key => $item) {
            $old_cx_img = $cms->metadata['cx_features'][$key]['img_path'] ?? null;
            if ($request->hasFile("cx_items.$key.image")) {
                if ($old_cx_img && File::exists(public_path($old_cx_img))) {
                    File::delete(public_path($old_cx_img));
                }
                $imgName = 'uploads/cms/cx_' . time() . "_{$slug}_{$key}." . $request->file("cx_items.$key.image")->extension();
                $request->file("cx_items.$key.image")->move(public_path('uploads/cms/'), $imgName);
                $cx_items[$key]['img_path'] = $imgName;
            } else {
                $cx_items[$key]['img_path'] = $old_cx_img;
            }
        }

        // ৭. Metadata Preparation
        $metadata = [
            'btn2_text'             => $request->btn2_text,
            'btn2_link'             => $request->btn2_link,
            'sec2_title'            => $request->sec2_title,
            'sec2_short'            => $request->sec2_short,
            'sec2_items'            => $request->sec2_items,
            'sec2_link_title'       => $request->sec2_link_title,
            'sec2_link_url'         => $request->sec2_link_url,
            'feature_title'         => $request->feature_title,
            'feature_short'         => $request->feature_short,
            'feature_list'          => $request->feature_list,
            'cx_title'              => $request->cx_title,
            'cx_description'        => $request->cx_description,
            'cx_link_title'         => $request->cx_link_title,
            'cx_link_add'           => $request->cx_link_add,
            'cx_features'           => $cx_items,
            'stat_75'               => $request->stat_75,
            'stat_95'               => $request->stat_95,
            'stat_37'               => $request->stat_37,
            'industry_items'        => $industry_items,
            'case_description'      => $request->case_description,
            'case_image_subtile'    => $request->case_image_subtile,
            'industry_description'  => $request->industry_description,
            'faq'                   => $request->faq,
            'faq_title'             => $request->faq_title,
            'faq_discription'       => $request->faq_discription,
            'bottom_desc'           => $request->bottom_desc,
            'bottom_btn_title'      => $request->bottom_btn_title,
            'bottom_btn_link'       => $request->bottom_btn_link,
            'ai_agents_title'       => $request->ai_agents_title,
            'ai_agents_discription' => $request->ai_agents_discription,
            'ai_deployment'         => $request->ai_deployment,
        ];

        // ৮. ডাটাবেজে সেভ বা আপডেট করা
        CMS::updateOrCreate(
            [
                'page' => 'home',
                'slug' => $slug,
                'type' => $type
            ],
            [
                'section'   => 'home_cms',
                'title'     => $request->title,
                'sub_title' => $request->sub_title,
                'video'     => $videoPath,
                'image1'    => $image1,
                'image2'    => $image2,
                'image4'    => $bottomVideo, // বটম ভিডিও এখানে সেভ হবে
                'btn_text'  => $request->btn_text,
                'btn_link'  => $request->btn_link,
                'metadata'  => $metadata,
                'status'    => 'active'
            ]
        );

        return redirect()->route('admin.cms.home.cms.index', ['slug' => $slug, 'type' => $type])
                         ->with('t-success', Str::headline($slug) . ' Updated Successfully!');
    }
}
