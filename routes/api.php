<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\GitHubController;
use App\Http\Controllers\TemplatesController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\YouTubeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['cors']], function () {
    Route::get('webhook', [WebhookController::class, 'verify']);
    Route::post('webhook', [WebhookController::class, 'webhook']);
});

Route::get('get/{contact}', [WhatsAppController::class, 'get']);
Route::post('send/activity/issue', [WhatsAppController::class, 'sendActivityIssue']);
Route::post('send/contact', [WhatsAppController::class, 'sendContact']);
Route::get('audio/{media_id}', [WhatsAppController::class, 'getAudio']);
Route::get('youtube/audio/{media_id}', [YouTubeController::class, 'audio']);
Route::get('youtube/video/{media_id}', [YouTubeController::class, 'video']);

Route::post('github', [GitHubController::class, 'webhook']);
Route::get('github/banner/{username}', [GitHubController::class, 'banner']);

// Authenticated routes
Route::middleware(['auth.sode'])->group(function () {

    // Categories
    Route::get('/categories', [CategoriesController::class, 'all']);
    Route::post('/categories/paginate', [CategoriesController::class, 'paginate']);
    Route::post('/categories', [CategoriesController::class, 'create']);
    Route::delete('/categories/{id}', [CategoriesController::class, 'delete']);
    Route::patch('/categories/{id}', [CategoriesController::class, 'status']);

    // Templates
    Route::post('/templates/paginate', [TemplatesController::class, 'paginate']);
    Route::post('/templates', [TemplatesController::class, 'create']);
    Route::delete('/templates/{id}', [TemplatesController::class, 'delete']);
    Route::patch('/templates/{id}', [TemplatesController::class, 'status']);
});