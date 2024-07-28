<?php

namespace App\Services;

use App\Spiders\WebSpider;
use OpenAI;
use RoachPHP\Roach;
use RoachPHP\Spider\Configuration\Overrides;

class ArticleService
{
    public function summarize(string $url): array
    {
        $content = $this->scrapeArticle($url);

        $summary = $this->getChatGPTSummary($content);

        return ['url' => $url, 'summary' => $summary];
    }

    public function scrapeArticle($url): string
    {
        $articleContent = '';

        $items = Roach::collectSpider(WebSpider::class, new Overrides(startUrls: [$url]));

        $articleContent = $items[0]->all()['article'];

        return $articleContent;
    }

    public function getChatGPTSummary($content): string
    {
        try {
            $client = OpenAI::client(env('OPENAI_API_KEY'));

            $response = $client->completions()->create([
                'model'       => 'gpt-3.5-turbo-instruct',
                'prompt'      => "Summarize the following article:\n\n" . $content,
                'max_tokens'  => 150,
                'temperature' => 0
            ]);

            return $response->choices[0]->text;
        } catch (OpenAI\Exceptions\ErrorException $th) {
            return $th->getMessage();
        }
    }
}
