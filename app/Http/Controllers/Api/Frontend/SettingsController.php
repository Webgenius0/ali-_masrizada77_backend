<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Footer;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'english');

        // 1. Fetch Settings with English Fallback
        $settings = Setting::where('type', $type)->first()
                    ?? Setting::where('type', 'english')->first();

        // 2. Fetch Footer Data
        $footerData = Footer::where('type', $type)
            ->where('status', 'active')
            ->orderBy('order')
            ->get();

        // Fallback to English if the requested language footer is empty
        if ($footerData->isEmpty() && $type !== 'english') {
            $footerData = Footer::where('type', 'english')
                ->where('status', 'active')
                ->orderBy('order')
                ->get();
        }

        // 3. Format Footer via Laravel Collections
        $formattedFooter = $footerData->groupBy('category')->map(function ($items, $category) {
            return [
                'title' => $category,
                'links' => $items->map(function ($item) {
                    return [
                        'label' => $item->title,
                        'path'  => $item->url ?? '#',
                    ];
                })->values()
            ];
        })->values();

        // 4. Return Response
        $data = [
            'settings' => $settings,
            'footer'   => $formattedFooter,
        ];

        return Helper::jsonResponse(true, 'Settings retrieved successfully', 200, $data);
    }
}
