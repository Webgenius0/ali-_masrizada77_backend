<?php
namespace App\Http\Controllers\Api\Frontend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller{
    public function index(Request $request){
        $type = $request->get('type', 'english');
        $settings = Setting::where('type', $type)->first();
        
        // If not found, fallback to english or first record
        if (!$settings) {
            $settings = Setting::where('type', 'english')->first();
        }

        $data = [
            'settings' => $settings
        ];
        return Helper::jsonResponse(true, 'Settings retrieved successfully', 200, $data);
    }
}