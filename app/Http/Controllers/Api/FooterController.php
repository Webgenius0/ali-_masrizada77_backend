<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Footer;

class FooterController extends Controller
{
    public function getFooterContent(Request $request)
    {
        $type = $request->query('type', 'english');

        $footers = Footer::where('type', $type)
            ->where('status', 'active')
            ->orderBy('order')
            ->get();
            // ->groupBy('category');

        return response()->json([
            'status' => 'success',
            'data' => $footers
        ]);
    }
}
