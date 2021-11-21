<?php

namespace App\Http\Controllers;

use App\Facades\FalconApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModeratorController extends Controller
{

    protected $prefix = 'fim_';

    /**
     * process
     *
     * @param Request $request
     * @access public
     * @return response
     */
    public function process(Request $request)
    {
        //provide your solution here.
        try {
            // rollback if something goes wrong
            return DB::transaction(function () use ($request) {
                // process the request to falcon api
                list('data' => $data, 'status' => $status) = FalconApi::request($request);

                // return the result to client
                return response()->json($data, $status);
            });
        } catch (\Exception $e) {
            // return error
            return response($e, 500);
        }
    } // End function process
}
