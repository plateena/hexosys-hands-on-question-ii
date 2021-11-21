<?php

namespace App\Services;

use App\Events\ModerationSuccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FalconApiServices
{
    protected $url = 'https://asia-east2-falcon-293005.cloudfunctions.net/falcon';
    protected $minConfidence = "0.50";
    protected $binaryImage;

    /**
     * request
     *
     * Make a request to falcon api  and fixed the json then return the result
     *
     * @param Request $request
     * @access public
     * @return array(fixed json result, falcon api status)
     */
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

        $fixedJson = json_decode($this->fixJson($response->body()));
        if ($response->status() == 200 && preg_match("/.+'Message':\ +'succeed'/", $response->body())) {
            // dispatch event falcon api success aka file modetation check
            ModerationSuccess::dispatch([$fixedJson, $content]);
        }

        return ['data' => $fixedJson, 'status' => $response->status()];
    } // End function request

    /**
     * getBinaryImage
     *
     * return the binary image data
     *
     * @access protected
     * @return binary
     */
    protected function getBinaryImage()
    {
        return $this->binaryImage;
    } // End function getBinaryImage

    /**
     * fixJson
     *
     * Fix the json response from the falcon api
     *
     * @param string $rsJson
     * @access protected
     * @return string
     */
    protected function fixJson(string $rsJson): string
    {
        return preg_replace_callback(
            "/\ (\d+\.?\d+)(,)/",
            array(get_class($this), "addMissingQuote"),
            str_replace("'", '"', $rsJson)
        );
    } // End function fixJson

    /**
     * addMissingQuote
     *
     * Add missing quote to the bad json
     *
     * @param array $str
     * @access protected
     * @return string
     */
    protected function addMissingQuote(array $str): string
    {
        return '"' . $str[1] . '"' . $str[2];
    } // End function addMissingQuote

    /**
     * getContent
     *
     * Check the client data, if it url the fetch the file and get the binary
     * else just return the binary content
     *
     * @param Request $request
     * @access protected
     * @return string
     */
    protected function getContent(Request $request): string
    {
        // check if content binary file
        if ($request->getContent() == '') {
            $this->binaryImage = Http::get(
                "http://" . // NOSONAR
                    $request->get("imageUrl")
            )->body();
        } else {
            $this->binaryImage = $request->getContent();
        }


        return $this->binaryImage;
    } // End function getContent
}
