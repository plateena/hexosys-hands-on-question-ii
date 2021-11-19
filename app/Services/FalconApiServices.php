<?php

namespace App\Services;

use App\Events\ModerationSuccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FalconApiServices
{
    protected $url = 'https://asia-east2-falcon-293005.cloudfunctions.net/falcon';
    protected $minConfidence = "0.50";
    protected $binaryImage;

    public function request(Request $request): array
    {
        $fixedJson = [];
        $content = $this->getContent($request);
        $response = Http::withBody(
            $content,
            'image/jpeg'
        )->withHeaders([
            "Min-Confidence" => '0.50'
        ])
            ->post($this->url);

        if ($response->status() === 200) {
            $fixedJson = json_decode($this->fixJson($response->body()));
            ModerationSuccess::dispatch([$fixedJson, $content]);
        }
        return ['data' => $fixedJson, 'status' => $response->status()];
    }

    public function getBinaryImage()
    {
        return $this->binaryImage;
    }

    private function fixJson(string $rsJson): string
    {
        return preg_replace_callback(
            "/\ (\d+\.?\d+)(,)/",
            array(get_class($this), "addMissingQuote"),
            str_replace("'", '"', $rsJson)
        );
    }

    private function addMissingQuote(array $str): string
    {
        return '"' . $str[1] . '"' . $str[2];
    }

    private function getContent(Request $request): string
    {
        // check if content binary file
        if (ctype_print($request->getContent())) {
            $this->binaryImage = Http::get("http://" . $request->get("imageUrl"))->body();
        } else {
            $this->binaryImage = $request->getContent();
        }

        return $this->binaryImage;
    }
}
