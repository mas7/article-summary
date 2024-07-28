<?php

namespace App\Spiders;

use Generator;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;
use RoachPHP\Extensions\LoggerExtension;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;

class WebSpider extends BasicSpider
{
    public array $startUrls = [
        'https://kelmarmon.medium.com/what-coffee-can-teach-you-about-marketing-a88ba1e1d93b'
    ];

    public array $downloaderMiddleware = [
        RequestDeduplicationMiddleware::class,
    ];

    public array $spiderMiddleware = [
        //
    ];

    public array $itemProcessors = [
        //
    ];

    public array $extensions = [
        LoggerExtension::class,
        StatsCollectorExtension::class,
    ];

    public int $concurrency = 2;

    public int $requestDelay = 1;

    /**
     * @return Generator<ParseResult>
     */
    public function parse(Response $response): Generator
    {
        $paragraphs = $response
            ->filter('p.pw-post-body-paragraph')
            ->each(function ($node) {
                return $node->text();
            });

        $article = implode("\n", $paragraphs);

        yield $this->item([
            'article' => $article,
        ]);
    }
}
