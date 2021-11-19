<?php

namespace App\Http\Controllers;

use App\Facades\FalconApi;
use App\Models\Sample;
use App\Models\SampleModerationLabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ModeratorController extends Controller
{
    public function process(Request $request)
    {
        //provide your solution here.
        try {
            list('data' => $data, 'status' => $status) = FalconApi::request($request);

            return response()->json($data, $status);
        } catch (\Exception $e) {
            return response($e, 500);
        }
    }
}
