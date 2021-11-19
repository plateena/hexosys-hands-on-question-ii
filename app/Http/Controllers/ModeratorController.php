<?php

namespace App\Http\Controllers;

use App\Facades\FalconApi;
use App\Models\Sample;
use App\Models\SampleModerationLabel;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ModeratorController extends Controller
{

    protected $prefix = 'fim_';

    public function process(Request $request)
    {
        //provide your solution here.
        try {
            return DB::transaction(function () use ($request) {
                list('data' => $data, 'status' => $status) = FalconApi::request($request);


                return response()->json($data, $status);
            });
        } catch (\Exception $e) {
            // @FIXME: Dump for debug
            dump($e);
            return response($e, 500);
        }
    }
}
