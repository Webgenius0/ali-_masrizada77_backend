<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ElevenLabsController extends Controller
{
    public function testVoice()
    {
        return view('backend.layouts.cms.voice_test');
    }
}
