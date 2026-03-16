<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cmsItems = Cms::latest()->paginate(15);
        return view('backend.cms.index', compact('cmsItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.cms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'page'          => ['required', 'string', 'max:100'],
            'section'       => ['required', 'string', 'max:100'],
            'name'          => ['nullable', 'string', 'max:150'],
            'slug'          => ['nullable', 'string', 'max:150', 'unique:c_m_s,slug'],
            'title'         => ['nullable', 'string', 'max:255'],
            'sub_title'     => ['nullable', 'string', 'max:255'],
            'description'   => ['nullable'],
            'sub_description' => ['nullable'],
            'bg'            => ['nullable', 'image', 'max:5120'], // 5MB
            'image'         => ['nullable', 'image', 'max:5120'], // poster or main image
            'btn_text'      => ['nullable', 'string', 'max:100'],
            'btn_link'      => ['nullable', 'url', 'max:255'],
            'btn_color'     => ['nullable', 'string', 'max:50'],
            'metadata'      => ['nullable', 'json'],
            'status'        => ['required', Rule::in(['active', 'inactive'])],
            'is_display'    => ['boolean'],
        ]);

        $data = $validated;

        // Handle background image
        if ($request->hasFile('bg')) {
            $data['bg'] = $request->file('bg')->store('cms/backgrounds', 'public');
        }

        // Handle main image / poster
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('cms/images', 'public');
        }

        // metadata যদি string আসে তাহলে json_encode করা লাগবে না (যদি frontend থেকে json পাঠাও)
        // কিন্তু textarea থেকে আসলে json_encode করতে পারো
        if ($request->filled('metadata') && is_string($request->metadata)) {
            $data['metadata'] = $request->metadata;
        }

        Cms::create($data);

        return redirect()
            ->route('admin.cms.index')
            ->with('success', 'CMS section created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cms $cm)
    {
        return view('backend.cms.edit', compact('cm'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cms $cm)
    {
        $validated = $request->validate([
            'page'          => ['required', 'string', 'max:100'],
            'section'       => ['required', 'string', 'max:100'],
            'name'          => ['nullable', 'string', 'max:150'],
            'slug'          => ['nullable', 'string', 'max:150', Rule::unique('c_m_s')->ignore($cm->id)],
            'title'         => ['nullable', 'string', 'max:255'],
            'sub_title'     => ['nullable', 'string', 'max:255'],
            'description'   => ['nullable'],
            'sub_description' => ['nullable'],
            'bg'            => ['nullable', 'image', 'max:5120'],
            'image'         => ['nullable', 'image', 'max:5120'],
            'btn_text'      => ['nullable', 'string', 'max:100'],
            'btn_link'      => ['nullable', 'url', 'max:255'],
            'btn_color'     => ['nullable', 'string', 'max:50'],
            'metadata'      => ['nullable', 'json'],
            'status'        => ['required', Rule::in(['active', 'inactive'])],
            'is_display'    => ['boolean'],
        ]);

        $data = $validated;

        if ($request->hasFile('bg')) {
            if ($cm->bg) {
                Storage::disk('public')->delete($cm->bg);
            }
            $data['bg'] = $request->file('bg')->store('cms/backgrounds', 'public');
        }

        if ($request->hasFile('image')) {
            if ($cm->image) {
                Storage::disk('public')->delete($cm->image);
            }
            $data['image'] = $request->file('image')->store('cms/images', 'public');
        }

        $cm->update($data);

        return redirect()
            ->route('admin.cms.index')
            ->with('success', 'CMS section updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cms $cm)
    {
        if ($cm->bg) Storage::disk('public')->delete($cm->bg);
        if ($cm->image) Storage::disk('public')->delete($cm->image);

        $cm->delete();

        return redirect()
            ->route('admin.cms.index')
            ->with('success', 'CMS section deleted successfully!');
    }
}
