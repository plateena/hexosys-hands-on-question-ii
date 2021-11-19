<?php

namespace Tests\Feature\FalconApi;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Tests\traits\WithLoginUser;

class FalconApiProxyTest extends TestCase
{

    use WithLoginUser;

    protected $imageFile = '/../../public/money.jpg';
    protected $url = "/api/moderate";

    /**
     * A basic feature test example.
     *
     * @test
     */
    public function can_use_falcon_api_with_binary_image()
    {

        $response = $this->actingAs($this->user)
            ->call(
                'POST',
                $this->url,
                [], // params
                [], // cookie
                [], // files
                [
                    'Content-Type' => 'image/jpeg',
                ], // server
                file_get_contents(dirname(__FILE__) . $this->imageFile), // content
            );

        $response->assertOk();
        $response->assertJsonFragment(["Version" => "1.1 Beta", "Message" => "succeed"]);
    }

    /**
     * A basic feature test example.
     *
     * @test
     */
    public function can_use_falcon_api_with_url_image()
    {
        $imgUrl = 'get-file-domain.com/money.jpg';
        Http::fake([
            'http://' . $imgUrl
            => Http::response(
                file_get_contents(dirname(__FILE__) . $this->imageFile),
                200
            )
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(
                $this->url,
                ['imageUrl' => $imgUrl]
            );

        $response->assertOk();
        $response->assertJsonFragment(["Version" => "1.1 Beta", "Message" => "succeed"]);
    }
}
