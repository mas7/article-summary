<?php

namespace App\Http\Controllers;

use App\Http\Requests\SummarizeRequest;
use App\Services\ArticleService;

class ArticleController extends Controller
{
    public function __construct(private ArticleService $service)
    {
        // 
    }

    public function index()
    {
        return view('index', ['summary' => '']);
    }

    public function summarize(SummarizeRequest $request)
    {
        $body = $request->validated();

        $serviceResponse = $this->service->summarize(data_get($body, 'url'));

        return view('index', $serviceResponse);
    }
}
