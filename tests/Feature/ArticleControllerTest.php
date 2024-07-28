<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\ArticleService;

class ArticleControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertViewHas('summary', '');
    }

    public function testSummarize()
    {
        $url = 'https://example.com';
        $summary = 'Summary of example article content';

        // Mocking the ArticleService used by the controller
        $this->mock(ArticleService::class, function ($mock) use ($url, $summary) {
            $mock->shouldReceive('summarize')->with($url)->andReturn(['url' => $url, 'summary' => $summary]);
        });

        $response = $this->post('/summarize', ['url' => $url]);

        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertViewHas('summary', $summary);
    }
}
