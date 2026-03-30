<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\Setting;
use Exception;

class LogoController extends Controller
{
 public function showlogo()
{
    $data = Setting::latest()->first(['logo', 'logo_height', 'logo_width']);

    if (!$data) {
        return response()->json([
            'success' => false,
            'message' => 'Data not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'logo' => asset($data->logo),
        'height' => $data->logo_height,
        'width' => $data->logo_width,
    ], 200);
}
}
