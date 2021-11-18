<?php

namespace App\Http\Controllers;

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
        $url = 'https://asia-east2-falcon-293005.cloudfunctions.net/falcon';
        try {
            $response = Http::withBody(
                $this->getContent($request),
                'image/jpeg'
            )->withHeaders([
                "Min-Confidence" => '0.50'
            ])
                ->post($url);

            return response($this->fixJson($response->body()), $response->status());
        } catch (\Exception $e) {
            return response($e, 500);
        }
    }

    protected function fixJson(string $rsJson): string
    {
        return preg_replace_callback(
            "/\ (\d+\.?\d+)(,)/",
            array(get_class($this), "addMissingQuote"),
            str_replace("'", '"', $rsJson)
        );
    }

    protected function addMissingQuote(array $str): string
    {
        return '"' . $str[1] . '"' . $str[2];
    }

    private function getContent(Request $request): string
    {
        if ($request->header('Content-Type') == 'image/jpeg') {
            return $request->getContent();
        }
        return Http::get("http://" . $request->get("imageUrl"))->body();
    }
}
