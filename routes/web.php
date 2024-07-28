<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ArticleController::class, 'index']);
Route::post('/summarize', [ArticleController::class, 'summarize'])->name('summarize');
