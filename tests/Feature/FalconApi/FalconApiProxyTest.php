<?php

namespace Tests\Feature\FalconApi;

use App\Models\Sample;
use App\Models\SampleModerationLabel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\traits\WithLoginUser;

class FalconApiProxyTest extends TestCase
{

    use RefreshDatabase, WithLoginUser;

    protected $imageFile = '/../../public/money.jpg';
    protected $url = "/api/moderate";

    /**
     * A basic feature test example.
     *
     * @test
     */
    public function can_use_falcon_api_with_binary_image()
    {
        $this->clearPublicFile();

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

        $files = Storage::disk('public')->allFiles();
        $this->assertCount(1, $files, "No file was created");
        $this->assertMatchesRegularExpression('/^fim_.+\..+$/', $files[0], "File naming convention faulty");

        $this->assertDatabaseCount('samples', 1);
        $this->assertDatabaseCount('sample_moderation_labels', collect($response->json('ModerationLabels'))->count());

        $this->clearPublicFile();
    }

    /**
     * A basic feature test example.
     *
     * @test
     */
    public function can_use_falcon_api_with_url_image()
    {
        $this->clearPublicFile();

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

        $files = Storage::disk('public')->allFiles();
        $this->assertCount(1, $files, "No file was created");
        $this->assertMatchesRegularExpression('/^fim_.+\..+$/', $files[0], "File naming convention faulty");

        $this->clearPublicFile();
    }

    private function clearPublicFile()
    {
        $files = Storage::disk('public')->allFiles("./");
        Storage::disk('public')->delete($files);
    }
}
