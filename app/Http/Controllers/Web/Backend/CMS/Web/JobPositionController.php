<?php

namespace App\Http\Controllers\Web\Backend\CMS\Web;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use Illuminate\Http\Request;

class JobPositionController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'english');

        $data = CMS::where('slug', 'job_position_options')
                   ->where('type', $type)
                   ->first();

        return view('backend.layouts.cms.job_positions', compact('data', 'type'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'type' => 'required|in:english,de',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:255',
        ]);

        // Filter out empty options
        $options = array_values(array_filter($request->options ?? []));

        CMS::updateOrCreate(
            [
                'slug' => 'job_position_options',
                'type' => $request->type,
            ],
            [
                'page'     => 'careers',
                'section'  => 'position_options',
                'metadata' => [
                    'options' => $options,
                ],
                'status'   => 'active',
            ]
        );

        return redirect()->back()->with('t-success', 'Job Positions updated successfully for ' . ucfirst($request->type));
    }
}
