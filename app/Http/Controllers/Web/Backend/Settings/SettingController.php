<?php

namespace App\Http\Controllers\Web\Backend\Settings;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function __construct()
    {
        View::share('crud', 'general_settings');
    }

    public function index(Request $request)
    {
        $type = $request->get('type', 'english');
        $setting = Setting::where('type', $type)->first();
        return view('backend.layouts.settings.general_settings', compact('setting', 'type'));
    }

    /**
     * Update the system settings.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'type'           => 'required|in:english,de,others',
            'name'           => 'nullable|string|max:50',
            'title'          => 'nullable|string|max:255',
            'description'    => 'nullable|string|max:500',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|string|email|max:100',
            'copyright'      => 'nullable|string|max:255',
            'keywords'       => 'nullable|string|max:255',
            'author'         => 'nullable|string|max:100',
            'address'        => 'nullable|string|max:255',
            'favicon'        => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
            'thumbnail'      => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
        ]);

        try {
            $setting = Setting::where('id', 1)->first(); // Default fallback or check by type
            
            // If updating a specific type, we should fetch that specific one
            $setting = Setting::where('type', $request->type)->first();

            if ($request->hasFile('favicon')) {
                if ($setting && $setting->favicon && file_exists(public_path($setting->favicon))) {
                    Helper::fileDelete(public_path($setting->favicon));
                }
                $validatedData['favicon'] = Helper::fileUpload($request->file('favicon'), 'settings', time() . '_' . getFileName($request->file('favicon')));
            }

            if ($request->hasFile('thumbnail')) {
                if ($setting && $setting->thumbnail && file_exists(public_path($setting->thumbnail))) {
                    Helper::fileDelete(public_path($setting->thumbnail));
                }
                $validatedData['thumbnail'] = Helper::fileUpload($request->file('thumbnail'), 'settings', time() . '_' . getFileName($request->file('thumbnail')));
            }

            Setting::updateOrCreate(['type' => $request->type], $validatedData);
            
            Cache::forget('settings');

            return back()->with('t-success', 'Updated successfully');

        } catch (Exception $e) {
            return back()->with('t-error', 'Failed to update' . $e->getMessage());
        }
    }
}
