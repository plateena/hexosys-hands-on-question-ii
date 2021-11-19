<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FalconApiServices
{
    protected $url = 'https://asia-east2-falcon-293005.cloudfunctions.net/falcon';
    protected $minConfidence = "0.50";

    public function request(Request $request): array
    {
        $response = Http::withBody(
            $this->getContent($request),
            'image/jpeg'
        )->withHeaders([
            "Min-Confidence" => '0.50'
        ])
            ->post($this->url);

        return ['data' => json_decode($this->fixJson($response->body())), 'status' => $response->status()];
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
        // check if content binary file
        if (ctype_print($request->getContent())) {
            return Http::get("http://" . $request->get("imageUrl"))->body();
        }
        return $request->getContent();
    }
}
